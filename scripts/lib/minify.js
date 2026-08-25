/**
 * Minify CSS and JS with esbuild inside the dist copy only.
 * style.css keeps a readable WordPress theme header block at the top.
 * Relative url(...) and @font-face unicode-range values are preserved.
 */

import fs from 'node:fs/promises';
import path from 'node:path';
import * as esbuild from 'esbuild';
import { listFiles } from './list-files.js';

const WP_HEADER = /^(\s*\/\*![\s\S]*?\*\/)/;
const WP_HEADER_PLAIN = /^(\s*\/\*[\s\S]*?\*\/)/;

/**
 * @param {string} css
 * @returns {{ header: string, body: string }}
 */
export function splitWpStyleHeader(css) {
	const important = css.match(WP_HEADER);
	if (important) {
		return { header: important[1].trim(), body: css.slice(important[1].length) };
	}
	const plain = css.match(WP_HEADER_PLAIN);
	if (plain && /Theme Name\s*:/i.test(plain[1])) {
		return { header: plain[1].trim(), body: css.slice(plain[1].length) };
	}
	throw new Error('dist style.css is missing a WordPress theme header comment.');
}

/**
 * @param {string} source
 * @param {string} filename
 * @param {'css' | 'js'} loader
 * @returns {Promise<string>}
 */
async function minify(source, filename, loader) {
	const result = await esbuild.transform(source, {
		loader,
		minify: true,
		sourcemap: false,
		target: ['es2018'],
		legalComments: loader === 'css' ? 'none' : 'none',
		sourcefile: filename,
	});
	return result.code;
}

function countUrls(css) {
	return (css.match(/url\(/g) || []).length;
}

/**
 * @param {string} distTheme
 * @returns {Promise<{ css: string[], js: string[] }>}
 */
export async function minifyDistAssets(distTheme) {
	const files = await listFiles(distTheme);
	const cssFiles = files.filter((rel) => rel === 'style.css' || (rel.startsWith('assets/css/') && rel.endsWith('.css')));
	const jsFiles = files.filter((rel) => rel.startsWith('assets/js/') && rel.endsWith('.js'));

	for (const rel of cssFiles) {
		const abs = path.join(distTheme, rel);
		const original = await fs.readFile(abs, 'utf8');
		let output;

		if (rel === 'style.css') {
			const { header, body } = splitWpStyleHeader(original);
			const minifiedBody = (await minify(body, rel, 'css')).trim();
			output = `${header}\n${minifiedBody}\n`;
			if (!/Theme Name\s*:/i.test(output) || !/^Version:\s*\d+\.\d+\.\d+/m.test(output)) {
				throw new Error('Minified style.css lost Theme Name or Version header.');
			}
		} else {
			output = `${(await minify(original, rel, 'css')).trim()}\n`;
		}

		if (countUrls(original) !== countUrls(output)) {
			throw new Error(`Minify changed url() count in ${rel}. Aborting to protect @font-face paths.`);
		}
		if (rel.endsWith('fonts.css') && !output.includes('../fonts/')) {
			throw new Error('Minified fonts.css lost relative ../fonts/ paths.');
		}

		await fs.writeFile(abs, output);
	}

	for (const rel of jsFiles) {
		const abs = path.join(distTheme, rel);
		const original = await fs.readFile(abs, 'utf8');
		const minified = await minify(original, rel, 'js');
		await fs.writeFile(abs, minified.endsWith('\n') ? minified : `${minified}\n`);
	}

	return { css: cssFiles, js: jsFiles };
}
