const path = require('path');
const fs = require('fs');
const TerserPlugin = require('terser-webpack-plugin');

// Mencari semua script.js di dalam Pages/
function getAllScripts() {
  const baseDir = path.resolve(__dirname, '../../app/Pages');
  const scripts = [];

  function readDirRecursive(directory) {
    fs.readdirSync(directory).forEach(file => {
      const fullPath = path.join(directory, file);
      if (fs.statSync(fullPath).isDirectory()) {
        readDirRecursive(fullPath);
      } else if (file === 'script.js') {
        scripts.push(fullPath);
      }
    });
  }

  readDirRecursive(baseDir);
  return scripts;
}

module.exports = {
  mode: 'production', // Set mode to 'production' for minification
  entry: {
    'helpers.bundle': './helpers.js', // Entry point for helpers libraries
    'pagescript': getAllScripts() // Menggabungkan semua script.js dari Pages/
  },
  output: {
    filename: '[name].js', // Output filename pattern (e.g., helpers.bundle.js, pagescript.js)
    path: path.resolve(__dirname, 'assets/js'), // Output path
  },
  module: {
    rules: [
      {
        test: /\.js$/, // Process all .js files
        exclude: /node_modules/, // Exclude node_modules
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env'],
          },
        },
      },
    ],
  },
  optimization: {
    minimize: true, // Enable minification
    minimizer: [new TerserPlugin({
      terserOptions: {
        compress: {
          drop_console: true, // Optional: remove console logs
        },
      },
    })],
  },
  devtool: 'source-map', // Generate source maps for easier debugging
};
