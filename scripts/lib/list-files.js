/**
 * Recursive file listing (files only).
 */

import fs from 'node:fs/promises';
import path from 'node:path';
import { toPosix } from './paths.js';

/**
 * @param {string} root
 * @returns {Promise<string[]>} POSIX relative paths, sorted.
 */
export async function listFiles(root) {
	const out = [];

	async function walk(dir, rel) {
		const entries = await fs.readdir(dir, { withFileTypes: true });
		for (const entry of entries) {
			const childRel = rel ? `${rel}/${entry.name}` : entry.name;
			const childAbs = path.join(dir, entry.name);
			if (entry.isDirectory()) {
				await walk(childAbs, childRel);
			} else if (entry.isFile()) {
				out.push(toPosix(childRel));
			}
		}
	}

	await walk(root, '');
	out.sort((a, b) => a.localeCompare(b));
	return out;
}
