# 🤖 AI Developer Guidelines for This Project

**CRITICAL INSTRUCTION FOR AI AGENT**: 
If you are reading this, you are working on a codebase derived from the "Admin Template Base". To save maximum tokens and ensure absolute consistency in UI/UX, you **MUST** follow these rules strictly:

## 1. CRUD & Scaffolding (MOST IMPORTANT)
- **STRICTLY PROHIBITED to create modules, Controllers, or Views manually! Everything MUST be generated through the generator command (`generate:scaffold`) to be 100% compliant with the admin-template.** 
- NEVER create Models, Controllers, Migrations, or Views manually from scratch when the user asks for a new CRUD feature.
- **ALWAYS** use the custom scaffold generator command:
  ```bash
  php artisan generate:scaffold ModelName
  ```
- The `generate:scaffold` command in this project has been deeply customized to automatically output Glassmorphism UI, floating inputs (`x-input-floating`, `x-textarea-floating`), toggle switches, and PowerGrid tables. 
- Doing this manually wastes tokens and breaks the UI standard. Just run the command and follow its prompts or provide the schema JSON if supported.

### Advanced Generator Capabilities (USE THESE!)
Do not write custom logic for these features, the generator handles them natively:
- **Enums (PHP 8.1+)**: Pass `enum=App\Enums\StatusEnum(draft,published)` in your field schema. It will auto-generate the Enum class, rule validations, and frontend `<x-select>` dropdowns perfectly.
- **BelongsTo Relations**: Pass `foreignId:belongsTo(Category)`. It automatically adds the Eloquent relationship, injects the related data into the controller, and creates a dropdown in the view.
- **Soft Deletes**: Use the `--soft-deletes` flag when generating.
- **Auto Currency Formatting**: Use `currency` as the HTML type. It will integrate AutoNumeric.js for automatic Rp (Rupiah) formatting without extra code.
- **Rich Text & Tags**: Use `textarea` for TinyMCE rich text, or `tags` for Tagify inputs.

## 2. UI/UX Standards
- The project relies on TailwindCSS and custom components.
- If you must create a custom page that cannot be scaffolded, reuse the existing Blade components (e.g. `<x-input-floating>`, `<x-textarea-floating>`, `<x-toggle>`).
- Always maintain the dark mode compatibility (`dark:` classes) and the Glassmorphism aesthetic we established.
- Centralized toasts (`<x-toast>`) handle all session success/error flashes. Do not write manual HTML alerts.

## 3. Token Efficiency
- Do not explain standard Laravel concepts. The user is an experienced developer.
- Keep your responses short and action-oriented.
- Rely on existing commands and components.
- Do not write repetitive boilerplate (the template uses modular traits like `HasFileUpload` and `HasImportExport` to keep controllers thin).

**By following these rules, you will keep the user's token usage minimal and maintain a perfect architecture.**
