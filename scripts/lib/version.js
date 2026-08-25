/**
 * Single source of truth for theme version sync.
 * Always writes package.json, style.css header, and IMIDZH_VERSION together.
 */

import fs from 'node:fs';
import { FUNCTIONS_PHP, PACKAGE_JSON, STYLE_CSS } from './paths.js';

const SEMVER = /^(\d+)\.(\d+)\.(\d+)$/;

/**
 * @param {string} value
 * @returns {{ major: number, minor: number, patch: number, raw: string }}
 */
export function parseSemver(value) {
	const match = String(value).trim().match(SEMVER);
	if (!match) {
		throw new Error(`Invalid version "${value}". Expected x.y.z (e.g. 1.1.1).`);
	}
	return {
		major: Number(match[1]),
		minor: Number(match[2]),
		patch: Number(match[3]),
		raw: `${match[1]}.${match[2]}.${match[3]}`,
	};
}

/**
 * @param {string} current
 * @param {'patch' | 'minor' | 'major'} level
 * @returns {string}
 */
export function bumpVersion(current, level = 'patch') {
	const parsed = parseSemver(current);
	if (level === 'major') {
		return `${parsed.major + 1}.0.0`;
	}
	if (level === 'minor') {
		return `${parsed.major}.${parsed.minor + 1}.0`;
	}
	return `${parsed.major}.${parsed.minor}.${parsed.patch + 1}`;
}

function readStyleVersion(css) {
	const match = css.match(/^Version:\s*(\d+\.\d+\.\d+)/m);
	return match ? match[1] : null;
}

function readPhpVersion(php) {
	const match = php.match(/define\(\s*'IMIDZH_VERSION'\s*,\s*'(\d+\.\d+\.\d+)'\s*\)/);
	return match ? match[1] : null;
}

function readPackageVersion(json) {
	try {
		const pkg = JSON.parse(json);
		return typeof pkg.version === 'string' ? pkg.version : null;
	} catch {
		return null;
	}
}

/**
 * Canonical current version: package.json → style.css → functions.php.
 * @returns {string}
 */
export function readCurrentVersion() {
	const pkg = fs.existsSync(PACKAGE_JSON) ? readPackageVersion(fs.readFileSync(PACKAGE_JSON, 'utf8')) : null;
	if (pkg) {
		return parseSemver(pkg).raw;
	}

	const style = fs.existsSync(STYLE_CSS) ? readStyleVersion(fs.readFileSync(STYLE_CSS, 'utf8')) : null;
	if (style) {
		return parseSemver(style).raw;
	}

	const php = fs.existsSync(FUNCTIONS_PHP) ? readPhpVersion(fs.readFileSync(FUNCTIONS_PHP, 'utf8')) : null;
	if (php) {
		return parseSemver(php).raw;
	}

	throw new Error('Could not read a version from package.json, style.css, or functions.php.');
}

/**
 * Write the same version to package.json, style.css, and functions.php.
 * @param {string} version
 * @returns {{ version: string, previous: { package?: string, style?: string, php?: string } }}
 */
export function writeVersion(version) {
	const next = parseSemver(version).raw;
	const previous = {};

	function writeIfChanged(file, contents) {
		const current = fs.readFileSync(file, 'utf8');
		if (current !== contents) {
			fs.writeFileSync(file, contents);
		}
	}

	const pkgRaw = fs.readFileSync(PACKAGE_JSON, 'utf8');
	previous.package = readPackageVersion(pkgRaw) || undefined;
	const pkg = JSON.parse(pkgRaw);
	pkg.version = next;
	writeIfChanged(PACKAGE_JSON, `${JSON.stringify(pkg, null, 2)}\n`);

	const css = fs.readFileSync(STYLE_CSS, 'utf8');
	previous.style = readStyleVersion(css) || undefined;
	if (!/^Version:\s*\d+\.\d+\.\d+/m.test(css)) {
		throw new Error('style.css is missing a Theme Header Version: line.');
	}
	writeIfChanged(STYLE_CSS, css.replace(/^Version:\s*\d+\.\d+\.\d+/m, `Version: ${next}`));

	const php = fs.readFileSync(FUNCTIONS_PHP, 'utf8');
	previous.php = readPhpVersion(php) || undefined;
	if (!/define\(\s*'IMIDZH_VERSION'\s*,\s*'[^']+'\s*\)/.test(php)) {
		throw new Error("functions.php is missing define( 'IMIDZH_VERSION', ... ).");
	}
	writeIfChanged(
		FUNCTIONS_PHP,
		php.replace(/define\(\s*'IMIDZH_VERSION'\s*,\s*'[^']+'\s*\)/, `define( 'IMIDZH_VERSION', '${next}' )`)
	);

	return { version: next, previous };
}

/**
 * Make all three files match. Prefer explicit version, otherwise the canonical read.
 * @param {string | null} [explicit]
 * @returns {string}
 */
export function syncVersion(explicit = null) {
	const version = explicit ? parseSemver(explicit).raw : readCurrentVersion();
	writeVersion(version);
	return version;
}
