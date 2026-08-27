<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Default disk for file uploads
     */
    protected string $disk = 'public';

    /**
     * Default folder for uploads
     */
    protected string $folder = 'uploads';

    /**
     * Whether to automatically convert images to WebP format
     */
    protected bool $generateWebP = true;

    /**
     * WebP quality (0-100)
     */
    protected int $webpQuality = 85;

    /**
     * Whether to generate responsive images (mobile, tablet, desktop)
     */
    protected bool $generateResponsive = false;

    /**
     * Responsive image sizes
     * Format: ['device' => max_width]
     */
    protected array $responsiveSizes = [
        'mobile' => 480,
        'tablet' => 768,
        'desktop' => 1920,
    ];

    /**
     * Set the disk to use for uploads
     */
    public function disk(string $disk): self
    {
        $this->disk = $disk;
        return $this;
    }

    /**
     * Set the folder for uploads
     */
    public function folder(string $folder): self
    {
        $this->folder = $folder;
        return $this;
    }

    /**
     * Enable or disable WebP conversion for images
     */
    public function generateWebP(bool $generate = true): self
    {
        $this->generateWebP = $generate;
        return $this;
    }

    /**
     * Set WebP quality (0-100)
     */
    public function webpQuality(int $quality): self
    {
        $this->webpQuality = max(0, min(100, $quality));
        return $this;
    }

    /**
     * Enable or disable responsive image generation (mobile, tablet, desktop)
     */
    public function generateResponsive(bool $generate = true): self
    {
        $this->generateResponsive = $generate;
        return $this;
    }

    /**
     * Set responsive image sizes
     *
     * @param array $sizes Format: ['mobile' => 480, 'tablet' => 768, 'desktop' => 1920]
     * @return self
     */
    public function responsiveSizes(array $sizes): self
    {
        $this->responsiveSizes = $sizes;
        return $this;
    }

    /**
     * Upload a file and return the path to be stored in database
     *
     * @param UploadedFile|null $file The uploaded file
     * @param string|null $oldFilePath The old file path to delete (for updates)
     * @param string|null $customFolder Custom folder name (optional)
     * @return string|null The file path to store in database (desktop path if responsive), or null if no file
     *                     Note: For responsive images, only desktop path is returned for backward compatibility.
     *                     Use getAllResponsiveUrls() to get all device versions.
     */
    public function upload(?UploadedFile $file, ?string $oldFilePath = null, ?string $customFolder = null): ?string
    {
        // If no file provided, return null
        if (!$file || !$file->isValid()) {
            return null;
        }

        // Delete old file if exists
        if ($oldFilePath) {
            $this->delete($oldFilePath);
        }

        // Use custom folder if provided, otherwise use default
        $folder = $customFolder ?? $this->folder;

        // Check if file is an image
        $isImage = $this->isImage($file);

        // Check if image can be converted to WebP (excludes SVG)
        $canConvertToWebP = $this->canConvertToWebP($file);

        // If responsive images are enabled and it's a convertible image
        if ($isImage && $canConvertToWebP && $this->generateResponsive) {
            $paths = $this->uploadResponsiveImages($file, $folder);
            if ($paths && isset($paths['desktop'])) {
                // Return desktop path as primary (for backward compatibility)
                // Other versions (mobile, tablet) are automatically generated and can be accessed via getResponsiveUrl()
                return $paths['desktop'];
            }
            // If responsive generation fails, fallback to normal upload
        }

        // If it's a convertible image, try to convert to WebP directly
        if ($canConvertToWebP && $this->generateWebP) {
            // Generate unique filename with .webp extension for WebP conversion
            $filename = $this->generateUniqueFilename($file, true);
            $path = $this->convertImageToWebP($file, $folder, $filename);
            if ($path) {
                return $path;
            }
            // If conversion fails, fallback to original upload with original extension
        }

        // Generate unique filename with original extension
        // (for non-image files, if WebP conversion is disabled, or if WebP conversion failed)
        $filename = $this->generateUniqueFilename($file, false);

        // Store file as-is (non-image files or if WebP conversion is disabled)
        $path = $file->storeAs($folder, $filename, $this->disk);

        // Return path relative to storage (without disk prefix)
        // This will be stored in database
        return $path;
    }

    /**
     * Upload a file and return all responsive paths (mobile, tablet, desktop)
     * Use this method when you need all responsive versions
     *
     * @param UploadedFile|null $file The uploaded file
     * @param string|null $oldFilePath The old file path to delete (for updates)
     * @param string|null $customFolder Custom folder name (optional)
     * @return array|null Array with keys 'desktop', 'tablet', 'mobile' containing paths, or null if failed
     */
    public function uploadWithResponsive(?UploadedFile $file, ?string $oldFilePath = null, ?string $customFolder = null): ?array
    {
        // Temporarily enable responsive generation
        $originalResponsive = $this->generateResponsive;
        $this->generateResponsive = true;

        $desktopPath = $this->upload($file, $oldFilePath, $customFolder);

        // Restore original setting
        $this->generateResponsive = $originalResponsive;

        if (!$desktopPath) {
            return null;
        }

        // Return all responsive paths
        return [
            'desktop' => $desktopPath,
            'tablet' => $this->getResponsivePath($desktopPath, 'tablet'),
            'mobile' => $this->getResponsivePath($desktopPath, 'mobile'),
        ];
    }

    /**
     * Get responsive path from desktop path
     *
     * @param string $desktopPath
     * @param string $device
     * @return string
     */
    protected function getResponsivePath(string $desktopPath, string $device): string
    {
        $pathInfo = pathinfo($desktopPath);
        $baseFilename = $pathInfo['filename'];
        $dirname = $pathInfo['dirname'] !== '.' ? $pathInfo['dirname'] . '/' : '';
        $extension = $pathInfo['extension'] ?? 'webp';

        // Remove device suffix if exists
        $baseFilename = preg_replace('/_(mobile|tablet|desktop)$/', '', $baseFilename);

        return $dirname . $baseFilename . '_' . $device . '.' . $extension;
    }

    /**
     * Upload multiple files and return array of paths
     *
     * @param array $files Array of UploadedFile objects
     * @param array|null $oldFilePaths Array of old file paths to delete
     * @param string|null $customFolder Custom folder name (optional)
     * @return array Array of file paths
     */
    public function uploadMultiple(array $files, ?array $oldFilePaths = null, ?string $customFolder = null): array
    {
        $paths = [];

        foreach ($files as $file) {
            if ($file && $file->isValid()) {
                $paths[] = $this->upload($file, null, $customFolder);
            }
        }

        // Delete old files if provided
        if ($oldFilePaths) {
            foreach ($oldFilePaths as $oldPath) {
                $this->delete($oldPath);
            }
        }

        return array_filter($paths);
    }

    /**
     * Delete a file from storage
     * If responsive images were generated, deletes all versions (mobile, tablet, desktop)
     *
     * @param string|null $filePath The desktop file path stored in database
     * @return bool True if file was deleted, false otherwise
     */
    public function delete(?string $filePath): bool
    {
        if (!$filePath) {
            return false;
        }

        // Try to delete desktop file
        $deleted = $this->deleteSingleFile($filePath);

        // Also try to delete responsive versions if they exist
        $pathInfo = pathinfo($filePath);
        $baseFilename = $pathInfo['filename'];
        $dirname = $pathInfo['dirname'] !== '.' ? $pathInfo['dirname'] . '/' : '';
        $extension = $pathInfo['extension'] ?? 'webp';

        // Remove device suffix if exists to get base filename
        $baseFilename = preg_replace('/_(mobile|tablet|desktop)$/', '', $baseFilename);

        foreach ($this->responsiveSizes as $device => $size) {
            if ($device !== 'desktop') { // Desktop already deleted above
                $responsivePath = $dirname . $baseFilename . '_' . $device . '.' . $extension;
                $this->deleteSingleFile($responsivePath);
            }
        }

        return $deleted;
    }

    /**
     * Delete a single file from storage
     *
     * @param string $filePath The file path
     * @return bool True if file was deleted, false otherwise
     */
    protected function deleteSingleFile(string $filePath): bool
    {
        if (Storage::disk($this->disk)->exists($filePath)) {
            return Storage::disk($this->disk)->delete($filePath);
        }

        return false;
    }

    /**
     * Get the full URL for a file path
     *
     * @param string|null $filePath The file path stored in database
     * @return string|null The full URL or null if file doesn't exist
     */
    public function url(?string $filePath): ?string
    {
        if (!$filePath) {
            return null;
        }

        // Check if it's already a full URL
        if (filter_var($filePath, FILTER_VALIDATE_URL)) {
            return $filePath;
        }

        // Check if file exists
        if (!Storage::disk($this->disk)->exists($filePath)) {
            return null;
        }

        // Use asset() helper for public disk - this handles the storage link correctly
        if ($this->disk === 'public') {
            // For public disk, files are stored in storage/app/public
            // and accessible via public/storage (symlink)
            // So we need to use asset() helper with 'storage/' prefix
            return asset('storage/' . $filePath);
        }

        // For other disks, try to get URL from config
        $baseUrl = config("filesystems.disks.{$this->disk}.url", config('app.url'));
        return rtrim($baseUrl, '/') . '/' . ltrim($filePath, '/');
    }

    /**
     * Check if file exists
     *
     * @param string|null $filePath The file path stored in database
     * @return bool True if file exists
     */
    public function exists(?string $filePath): bool
    {
        if (!$filePath) {
            return false;
        }

        return Storage::disk($this->disk)->exists($filePath);
    }

    /**
     * Dangerous executable extensions that should never be stored directly
     */
    protected static array $blockedExtensions = [
        'php', 'phtml', 'phar', 'php3', 'php4', 'php5', 'php7', 'php8', 'pht', 'phps',
        'exe', 'sh', 'bash', 'pl', 'py', 'cgi', 'htm', 'html', 'js', 'jsp', 'asp', 'aspx', 'bat', 'cmd',
    ];

    /**
     * Generate a unique filename
     *
     * @param UploadedFile $file
     * @param bool $isImage Whether the file is an image (will use .webp extension)
     * @return string
     */
    protected function generateUniqueFilename(UploadedFile $file, bool $isImage = false): string
    {
        $rawExtension = strtolower($file->getClientOriginalExtension());

        // ponytail: Block executable scripts from being stored in public storage to prevent arbitrary script execution.
        if (in_array($rawExtension, self::$blockedExtensions, true)) {
            $extension = 'bin';
        } else {
            $extension = $isImage ? 'webp' : $rawExtension;
        }

        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

        // Sanitize filename
        $sanitizedName = Str::slug($originalName);

        // Generate unique filename with timestamp
        $filename = $sanitizedName . '_' . time() . '_' . Str::random(8) . '.' . $extension;

        return $filename;
    }

    /**
     * Check if file is an image
     *
     * @param UploadedFile $file
     * @return bool
     */
    protected function isImage(UploadedFile $file): bool
    {
        $mimeType = $file->getMimeType();
        return str_starts_with($mimeType, 'image/');
    }

    /**
     * Check if image can be converted to WebP
     * SVG and ICO files cannot be converted to WebP using GD library
     *
     * @param UploadedFile $file
     * @return bool
     */
    protected function canConvertToWebP(UploadedFile $file): bool
    {
        if (!$this->isImage($file)) {
            return false;
        }

        $mimeType = $file->getMimeType();
        $extension = strtolower($file->getClientOriginalExtension());

        // SVG files cannot be converted to WebP using GD library
        if ($mimeType === 'image/svg+xml' || $extension === 'svg') {
            return false;
        }

        // ICO files cannot be converted to WebP (palette image not supported)
        if ($mimeType === 'image/x-icon' || $mimeType === 'image/vnd.microsoft.icon' || $extension === 'ico') {
            return false;
        }

        return true;
    }

    /**
     * Convert palette image to truecolor image
     * WebP format doesn't support palette images, so we need to convert them
     *
     * @param resource $image The image resource
     * @return resource The converted image resource (truecolor)
     */
    protected function convertPaletteToTrueColor($image)
    {
        if (!is_resource($image) && !($image instanceof \GdImage)) {
            return $image;
        }

        // Check if image is already truecolor
        if (\imageistruecolor($image)) {
            return $image;
        }

        // Use imagepalettetotruecolor() if available (PHP 5.5.5+)
        if (function_exists('imagepalettetotruecolor')) {
            \imagepalettetotruecolor($image);
            return $image;
        }

        // Fallback: Create new truecolor image and copy pixels
        $width = \imagesx($image);
        $height = \imagesy($image);
        $truecolorImage = \imagecreatetruecolor($width, $height);

        // Preserve transparency
        \imagealphablending($truecolorImage, false);
        \imagesavealpha($truecolorImage, true);
        $transparent = \imagecolorallocatealpha($truecolorImage, 255, 255, 255, 127);
        \imagefill($truecolorImage, 0, 0, $transparent);
        \imagealphablending($truecolorImage, true);

        // Copy pixels
        \imagecopy($truecolorImage, $image, 0, 0, 0, 0, $width, $height);

        // Destroy old image
        \imagedestroy($image);

        return $truecolorImage;
    }

    /**
     * Convert uploaded image directly to WebP format
     *
     * @param UploadedFile $file The uploaded image file
     * @param string $folder The folder to store the file
     * @param string $filename The target filename (with .webp extension)
     * @return string|null Path to WebP file or null if failed
     */
    protected function convertImageToWebP(UploadedFile $file, string $folder, string $filename): ?string
    {
        if (!extension_loaded('gd')) {
            return null;
        }

        // Double check: ICO files should not be converted to WebP
        $extension = strtolower($file->getClientOriginalExtension());
        if ($extension === 'ico') {
            return null;
        }

        // Get temporary file path
        $tempPath = $file->getRealPath();
        if (!file_exists($tempPath)) {
            return null;
        }

        // Get image info
        $imageInfo = @\getimagesize($tempPath);
        if (!$imageInfo) {
            return null;
        }

        $mimeType = $imageInfo['mime'];

        // Check MIME type for ICO files
        if ($mimeType === 'image/x-icon' || $mimeType === 'image/vnd.microsoft.icon') {
            return null;
        }

        // Create image resource based on MIME type
        $image = match ($mimeType) {
            'image/jpeg', 'image/jpg' => @\imagecreatefromjpeg($tempPath),
            'image/png' => @\imagecreatefrompng($tempPath),
            'image/gif' => @\imagecreatefromgif($tempPath),
            'image/webp' => @\imagecreatefromwebp($tempPath),
            default => null,
        };

        if (!$image) {
            return null;
        }

        // Generate WebP path
        $webpPath = $folder . '/' . $filename;
        $webpFullPath = Storage::disk($this->disk)->path($webpPath);

        // Ensure directory exists
        $webpDir = dirname($webpFullPath);
        if (!is_dir($webpDir)) {
            mkdir($webpDir, 0755, true);
        }

        // Handle PNG transparency
        if ($mimeType === 'image/png') {
            \imagealphablending($image, false);
            \imagesavealpha($image, true);
        }

        // Convert palette image to truecolor if needed (WebP doesn't support palette images)
        $image = $this->convertPaletteToTrueColor($image);

        // Generate WebP
        $success = \imagewebp($image, $webpFullPath, $this->webpQuality);
        \imagedestroy($image);

        if ($success && file_exists($webpFullPath)) {
            return $webpPath;
        }

        return null;
    }

    /**
     * Get WebP path from original image path
     *
     * @param string $imagePath
     * @return string
     */
    protected function getWebPPath(string $imagePath): string
    {
        $pathInfo = pathinfo($imagePath);
        return ($pathInfo['dirname'] !== '.' ? $pathInfo['dirname'] . '/' : '') . $pathInfo['filename'] . '.webp';
    }

    /**
     * Get WebP URL if exists, otherwise return original URL
     * Note: Images are now stored directly as WebP, so this returns the file URL
     *
     * @param string|null $filePath
     * @return string|null
     */
    public function getWebPUrl(?string $filePath): ?string
    {
        if (!$filePath) {
            return null;
        }

        // Images are now stored directly as WebP, so just return the URL
        // If it's already a WebP file, return it directly
        if (str_ends_with(strtolower($filePath), '.webp')) {
            return $this->url($filePath);
        }

        // For non-WebP files (legacy or non-image files), return original URL
        return $this->url($filePath);
    }

    /**
     * Static method for quick upload (for dropify and common use cases)
     *
     * @param UploadedFile|null $file
     * @param string|null $oldFilePath
     * @param string|null $folder
     * @return string|null
     */
    public static function uploadFile(?UploadedFile $file, ?string $oldFilePath = null, ?string $folder = null): ?string
    {
        return (new self())->folder($folder ?? 'uploads')->upload($file, $oldFilePath);
    }

    /**
     * Static method for quick delete
     *
     * @param string|null $filePath
     * @return bool
     */
    public static function deleteFile(?string $filePath): bool
    {
        return (new self())->delete($filePath);
    }

    /**
     * Static method for quick URL
     *
     * @param string|null $filePath
     * @return string|null
     */
    public static function getFileUrl(?string $filePath): ?string
    {
        return (new self())->disk('public')->url($filePath);
    }

    /**
     * Static method for quick WebP URL
     *
     * @param string|null $filePath
     * @return string|null
     */
    public static function getWebPFileUrl(?string $filePath): ?string
    {
        return (new self())->disk('public')->getWebPUrl($filePath);
    }

    /**
     * Static method for quick upload with responsive images
     * Returns desktop path (for backward compatibility with database)
     *
     * @param UploadedFile|null $file
     * @param string|null $oldFilePath
     * @param string|null $folder
     * @return string|null Desktop path
     */
    public static function uploadResponsiveFile(?UploadedFile $file, ?string $oldFilePath = null, ?string $folder = null): ?string
    {
        return (new self())
            ->folder($folder ?? 'uploads')
            ->generateResponsive(true)
            ->upload($file, $oldFilePath);
    }

    /**
     * Static method for quick upload with responsive images returning all paths
     *
     * @param UploadedFile|null $file
     * @param string|null $oldFilePath
     * @param string|null $folder
     * @return array|null Array with 'desktop', 'tablet', 'mobile' keys
     */
    public static function uploadResponsiveFileAll(?UploadedFile $file, ?string $oldFilePath = null, ?string $folder = null): ?array
    {
        return (new self())
            ->folder($folder ?? 'uploads')
            ->uploadWithResponsive($file, $oldFilePath, $folder);
    }

    /**
     * Static method for getting responsive URL
     *
     * @param string|null $filePath Desktop file path
     * @param string $device Device type: 'mobile', 'tablet', or 'desktop'
     * @return string|null
     */
    public static function getResponsiveFileUrl(?string $filePath, string $device = 'desktop'): ?string
    {
        return (new self())->disk('public')->getResponsiveUrl($filePath, $device);
    }

    /**
     * Static method for getting all responsive URLs
     *
     * @param string|null $filePath Desktop file path
     * @return array Array with 'mobile', 'tablet', 'desktop' keys
     */
    public static function getAllResponsiveFileUrls(?string $filePath): array
    {
        return (new self())->disk('public')->getAllResponsiveUrls($filePath);
    }

    /**
     * Upload image and generate responsive versions (mobile, tablet, desktop)
     *
     * @param UploadedFile $file The uploaded image file
     * @param string $folder The folder to store the files
     * @return array|null Array with keys 'desktop', 'tablet', 'mobile' containing paths, or null if failed
     */
    protected function uploadResponsiveImages(UploadedFile $file, string $folder): ?array
    {
        if (!extension_loaded('gd')) {
            return null;
        }

        // Double check: ICO files should not be processed
        $extension = strtolower($file->getClientOriginalExtension());
        if ($extension === 'ico') {
            return null;
        }

        // Get temporary file path
        $tempPath = $file->getRealPath();
        if (!file_exists($tempPath)) {
            return null;
        }

        // Get image info
        $imageInfo = @\getimagesize($tempPath);
        if (!$imageInfo) {
            return null;
        }

        $mimeType = $imageInfo['mime'];

        // Check MIME type for ICO files
        if ($mimeType === 'image/x-icon' || $mimeType === 'image/vnd.microsoft.icon') {
            return null;
        }
        $originalWidth = $imageInfo[0];
        $originalHeight = $imageInfo[1];

        // Create image resource based on MIME type
        $sourceImage = match ($mimeType) {
            'image/jpeg', 'image/jpg' => @\imagecreatefromjpeg($tempPath),
            'image/png' => @\imagecreatefrompng($tempPath),
            'image/gif' => @\imagecreatefromgif($tempPath),
            'image/webp' => @\imagecreatefromwebp($tempPath),
            default => null,
        };

        if (!$sourceImage) {
            return null;
        }

        // Handle PNG transparency
        if ($mimeType === 'image/png') {
            \imagealphablending($sourceImage, false);
            \imagesavealpha($sourceImage, true);
        }

        // Convert palette image to truecolor if needed (for WebP conversion)
        $sourceImage = $this->convertPaletteToTrueColor($sourceImage);

        // Generate base filename
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $sanitizedName = Str::slug($originalName);
        $baseFilename = $sanitizedName . '_' . time() . '_' . Str::random(8);
        $extension = $this->generateWebP ? 'webp' : strtolower($file->getClientOriginalExtension());

        $paths = [];
        $success = true;

        // Generate responsive versions
        foreach ($this->responsiveSizes as $device => $maxWidth) {
            // Calculate new dimensions maintaining aspect ratio
            if ($originalWidth <= $maxWidth) {
                // Image is smaller than target, use original size
                $newWidth = $originalWidth;
                $newHeight = $originalHeight;
            } else {
                // Resize to fit max width
                $ratio = $maxWidth / $originalWidth;
                $newWidth = $maxWidth;
                $newHeight = (int)($originalHeight * $ratio);
            }

            // Create resized image
            $resizedImage = \imagecreatetruecolor($newWidth, $newHeight);

            // Preserve transparency for PNG
            if ($mimeType === 'image/png') {
                \imagealphablending($resizedImage, false);
                \imagesavealpha($resizedImage, true);
                $transparent = \imagecolorallocatealpha($resizedImage, 255, 255, 255, 127);
                \imagefill($resizedImage, 0, 0, $transparent);
            }

            // Resize image
            \imagecopyresampled(
                $resizedImage,
                $sourceImage,
                0,
                0,
                0,
                0,
                $newWidth,
                $newHeight,
                $originalWidth,
                $originalHeight
            );

            // Generate filename for this device
            $deviceFilename = $baseFilename . '_' . $device . '.' . $extension;
            $devicePath = $folder . '/' . $deviceFilename;
            $deviceFullPath = Storage::disk($this->disk)->path($devicePath);

            // Ensure directory exists
            $deviceDir = dirname($deviceFullPath);
            if (!is_dir($deviceDir)) {
                mkdir($deviceDir, 0755, true);
            }

            // Save image
            if ($this->generateWebP && $extension === 'webp') {
                // Convert palette image to truecolor if needed (WebP doesn't support palette images)
                $resizedImage = $this->convertPaletteToTrueColor($resizedImage);
                $saved = \imagewebp($resizedImage, $deviceFullPath, $this->webpQuality);
            } else {
                $saved = match ($mimeType) {
                    'image/jpeg', 'image/jpg' => \imagejpeg($resizedImage, $deviceFullPath, 90),
                    'image/png' => \imagepng($resizedImage, $deviceFullPath, 9),
                    'image/gif' => \imagegif($resizedImage, $deviceFullPath),
                    default => false,
                };
            }

            \imagedestroy($resizedImage);

            if ($saved && file_exists($deviceFullPath)) {
                $paths[$device] = $devicePath;
            } else {
                $success = false;
            }
        }

        \imagedestroy($sourceImage);

        // Return paths if all were successful, otherwise return null
        return $success && count($paths) === count($this->responsiveSizes) ? $paths : null;
    }

    /**
     * Get responsive image URL for specific device
     *
     * @param string|null $filePath The desktop file path stored in database
     * @param string $device Device type: 'mobile', 'tablet', or 'desktop'
     * @return string|null The URL for the device-specific image
     */
    public function getResponsiveUrl(?string $filePath, string $device = 'desktop'): ?string
    {
        if (!$filePath) {
            return null;
        }

        // If desktop, return original path URL
        if ($device === 'desktop') {
            return $this->url($filePath);
        }

        // Generate responsive path from desktop path
        $responsivePath = $this->getResponsivePath($filePath, $device);

        // Check if responsive version exists
        if (Storage::disk($this->disk)->exists($responsivePath)) {
            return $this->url($responsivePath);
        }

        // Fallback to desktop path if responsive version doesn't exist
        return $this->url($filePath);
    }

    /**
     * Get all responsive image URLs
     *
     * @param string|null $filePath The desktop file path stored in database
     * @return array Array with keys 'mobile', 'tablet', 'desktop' containing URLs
     */
    public function getAllResponsiveUrls(?string $filePath): array
    {
        $urls = [];

        foreach ($this->responsiveSizes as $device => $size) {
            $urls[$device] = $this->getResponsiveUrl($filePath, $device);
        }

        return $urls;
    }
}
