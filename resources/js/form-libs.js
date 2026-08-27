/**
 * Form Component Libraries (bundled via NPM instead of CDN)
 * These are imported in form pages via @vite directive
 */

// TinyMCE Rich Text Editor
import 'tinymce/tinymce';
import 'tinymce/themes/silver';
import 'tinymce/icons/default';
import 'tinymce/models/dom';

// TinyMCE Free Plugins
import 'tinymce/plugins/anchor';
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/charmap';
import 'tinymce/plugins/code';
import 'tinymce/plugins/codesample';
import 'tinymce/plugins/directionality';
import 'tinymce/plugins/emoticons';
import 'tinymce/plugins/fullscreen';
import 'tinymce/plugins/help';
import 'tinymce/plugins/image';
import 'tinymce/plugins/insertdatetime';
import 'tinymce/plugins/link';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/media';
import 'tinymce/plugins/nonbreaking';
import 'tinymce/plugins/pagebreak';
import 'tinymce/plugins/preview';
import 'tinymce/plugins/searchreplace';
import 'tinymce/plugins/table';
import 'tinymce/plugins/visualblocks';
import 'tinymce/plugins/visualchars';
import 'tinymce/plugins/wordcount';

// TomSelect (searchable select)
import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.default.min.css';
window.TomSelect = TomSelect;

// Tagify (tag input)
import Tagify from '@yaireo/tagify';
import '@yaireo/tagify/dist/tagify.css';
window.Tagify = Tagify;

// AutoNumeric (currency formatting)
import AutoNumeric from 'autonumeric';
window.AutoNumeric = AutoNumeric;
