#!/usr/bin/env node

import fs from 'fs';
import path from 'path';

const viteManifestPath = path.resolve('public/build/.vite/manifest.json');
const targetPath = path.resolve('public/build/manifest.json');

try {
    if (fs.existsSync(viteManifestPath)) {
        const content = fs.readFileSync(viteManifestPath, 'utf-8');
        fs.writeFileSync(targetPath, content, 'utf-8');
        console.log('✓ Manifest moved to public/build/manifest.json');

        // Clean up .vite directory
        const viteDir = path.resolve('public/build/.vite');
        const files = fs.readdirSync(viteDir);
        
        for (const file of files) {
            fs.unlinkSync(path.join(viteDir, file));
        }
        fs.rmdirSync(viteDir);
        console.log('✓ .vite directory cleaned up');
    } else if (fs.existsSync(targetPath)) {
        console.log('✓ Manifest already at correct location');
    } else {
        console.error('✗ Manifest not found');
        process.exit(1);
    }
} catch (error) {
    console.error('✗ Error moving manifest:', error.message);
    process.exit(1);
}
