const archiver = require('archiver');
const fs = require('fs');
const path = require('path');

const pkg = require('../package.json');
const themeName = 'catalago-de-literatura';
const outputName = `${themeName}-v${pkg.version}.zip`;
const outputPath = path.join(__dirname, '..', '..', outputName);

// Arquivos/pastas excluídos do zip
const IGNORE = [
    'node_modules',
    'scripts',
    'assets/css/tailwind.css',
    'postcss.config.js',
    'package.json',
    'package-lock.json',
    '.git',
    '.gitignore',
    '.DS_Store',
];

const output = fs.createWriteStream(outputPath);
const archive = archiver('zip', { zlib: { level: 9 } });

output.on('close', () => {
    const kb = (archive.pointer() / 1024).toFixed(1);
    console.log(`✔ ${outputName} criado (${kb} KB)`);
});

archive.on('error', (err) => { throw err; });
archive.pipe(output);

archive.glob('**/*', {
    cwd: path.join(__dirname, '..'),
    root: themeName,
    ignore: IGNORE.flatMap(p => [p, `${p}/**`]),
    dot: false,
}, { prefix: themeName });

archive.finalize();
