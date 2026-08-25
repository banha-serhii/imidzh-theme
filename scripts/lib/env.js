/**
 * Load and validate deploy credentials from `.env` (never logged).
 */

import fs from 'node:fs';
import os from 'node:os';
import path from 'node:path';
import dotenv from 'dotenv';
import { ENV_FILE, THEME_ROOT } from './paths.js';

function expandHome(filePath) {
	if (!filePath) {
		return filePath;
	}
	if (filePath.startsWith('~/')) {
		return path.join(os.homedir(), filePath.slice(2));
	}
	return filePath;
}

function requiredMessage(missing) {
	return [
		'Missing deploy configuration. Copy .env.example to .env and set:',
		...missing.map((key) => `  ${key}`),
		'See docs/DEPLOY.md',
	].join('\n');
}

/**
 * @returns {{
 *   protocol: 'sftp' | 'ftps',
 *   host: string,
 *   port: number,
 *   user: string,
 *   password: string,
 *   privateKey: string,
 *   privateKeyPath: string,
 *   passphrase: string,
 *   remotePath: string,
 *   tlsRejectUnauthorized: boolean,
 * }}
 */
export function loadDeployConfig() {
	if (fs.existsSync(ENV_FILE)) {
		dotenv.config({ path: ENV_FILE });
	}

	const protocol = String(process.env.DEPLOY_PROTOCOL || 'sftp').trim().toLowerCase();
	if (protocol !== 'sftp' && protocol !== 'ftps') {
		throw new Error(`DEPLOY_PROTOCOL must be "sftp" or "ftps" (got "${protocol}").`);
	}

	const host = String(process.env.DEPLOY_HOST || '').trim();
	const user = String(process.env.DEPLOY_USER || '').trim();
	const remotePath = String(process.env.DEPLOY_REMOTE_PATH || '').trim().replace(/\/+$/, '');
	const password = process.env.DEPLOY_PASSWORD || '';
	const keyPathRaw = String(process.env.DEPLOY_PRIVATE_KEY || '').trim();
	const passphrase = process.env.DEPLOY_PRIVATE_KEY_PASSPHRASE || '';

	const missing = [];
	if (!host) {
		missing.push('DEPLOY_HOST');
	}
	if (!user) {
		missing.push('DEPLOY_USER');
	}
	if (!remotePath) {
		missing.push('DEPLOY_REMOTE_PATH');
	}

	if (missing.length) {
		throw new Error(requiredMessage(missing));
	}

	let privateKey = '';
	let privateKeyPath = '';
	if (keyPathRaw) {
		privateKeyPath = path.isAbsolute(expandHome(keyPathRaw))
			? expandHome(keyPathRaw)
			: path.resolve(THEME_ROOT, expandHome(keyPathRaw));
		if (!fs.existsSync(privateKeyPath)) {
			throw new Error(`DEPLOY_PRIVATE_KEY file not found: ${privateKeyPath}`);
		}
		privateKey = fs.readFileSync(privateKeyPath, 'utf8');
	}

	if (protocol === 'sftp' && !password && !privateKey) {
		throw new Error(
			requiredMessage(['DEPLOY_PASSWORD (or DEPLOY_PRIVATE_KEY)'])
		);
	}
	if (protocol === 'ftps' && !password) {
		throw new Error(requiredMessage(['DEPLOY_PASSWORD']));
	}

	const defaultPort = protocol === 'ftps' ? 21 : 22;
	const portRaw = String(process.env.DEPLOY_PORT || '').trim();
	const port = portRaw ? Number(portRaw) : defaultPort;
	if (!Number.isInteger(port) || port < 1 || port > 65535) {
		throw new Error(`DEPLOY_PORT is invalid: "${portRaw || port}"`);
	}

	const tlsRejectUnauthorized = String(process.env.DEPLOY_TLS_REJECT_UNAUTHORIZED || 'true').toLowerCase() !== 'false';

	return {
		protocol,
		host,
		port,
		user,
		password,
		privateKey,
		privateKeyPath,
		passphrase,
		remotePath,
		tlsRejectUnauthorized,
	};
}

/**
 * Safe subset for logs / dry-run output.
 * @param {ReturnType<typeof loadDeployConfig>} config
 */
export function publicDeployConfig(config) {
	return {
		protocol: config.protocol,
		host: config.host,
		port: config.port,
		user: config.user,
		remotePath: config.remotePath,
		auth: config.privateKeyPath ? `key (${config.privateKeyPath})` : 'password',
	};
}
