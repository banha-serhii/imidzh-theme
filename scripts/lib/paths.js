/**
 * Theme / dist locations and copy-exclusion rules.
 */

import path from 'node:path';
import { fileURLToPath } from 'node:url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));

export const THEME_ROOT = path.resolve(__dirname, '../..');
export const THEME_SLUG = 'imidzh-theme';
export const DIST_ROOT = path.join(THEME_ROOT, 'dist');
export const DIST_THEME = path.join(DIST_ROOT, THEME_SLUG);

export const STYLE_CSS = path.join(THEME_ROOT, 'style.css');
export const FUNCTIONS_PHP = path.join(THEME_ROOT, 'functions.php');
export const PACKAGE_JSON = path.join(THEME_ROOT, 'package.json');
export const ENV_FILE = path.join(THEME_ROOT, '.env');

const EXCLUDE_DIR_NAMES = new Set([
	'.git',
	'.cursor',
	'.idea',
	'.vscode',
	'node_modules',
	'dist',
	'build',
	'coverage',
	'scripts',
	'docs',
]);

const EXCLUDE_FILE_NAMES = new Set([
	'.gitignore',
	'.DS_Store',
	'Thumbs.db',
	'package.json',
	'package-lock.json',
	'.npmrc',
	'.editorconfig',
]);

const EXCLUDE_RELATIVE = new Set(['assets/img/logo-source.jpg']);

/**
 * @param {string} relativePosix Path relative to theme root, POSIX separators.
 * @returns {boolean}
 */
export function shouldExclude(relativePosix) {
	if (!relativePosix || relativePosix === '.') {
		return false;
	}

	const posix = relativePosix.replaceAll('\\', '/');
	const parts = posix.split('/');

	if (parts.some((part) => EXCLUDE_DIR_NAMES.has(part))) {
		return true;
	}

	const base = parts[parts.length - 1];

	if (base.startsWith('.env')) {
		return true;
	}
	if (base.endsWith('.map') || base.endsWith('.zip')) {
		return true;
	}
	if (EXCLUDE_FILE_NAMES.has(base)) {
		return true;
	}
	if (EXCLUDE_RELATIVE.has(posix)) {
		return true;
	}

	return false;
}

/**
 * @param {string} filePath
 * @returns {string}
 */
export function toPosix(filePath) {
	return filePath.split(path.sep).join('/');
}
