/**
 * FilePond file upload library (bundled via NPM instead of CDN)
 * Imported in filepond component via @vite directive
 */

import * as FilePond from 'filepond';
import FilePondPluginImagePreview from 'filepond-plugin-image-preview';

import 'filepond/dist/filepond.min.css';
import 'filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css';

FilePond.registerPlugin(FilePondPluginImagePreview);

window.FilePond = FilePond;
