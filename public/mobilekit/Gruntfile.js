module.exports = function(grunt) {
    // Konfigurasi tugas
    grunt.initConfig({
      concat: {
        dist: {
          src: [], // Akan diisi secara dinamis di bawah
          dest: 'assets/js/pagescript.js' // File output yang digabungkan
        }
      },
      uglify: {
        dist: {
          files: {
            'assets/js/pagescript.min.js': ['assets/js/pagescript.js'] // Minify file
          }
        }
      },
      watch: {
        scripts: {
          files: ['../../app/Views/Pages/**/*.js'], // Memantau semua .js file di dalam Pages/ dan subfoldernya
          tasks: ['concat', 'uglify'],
          options: {
            spawn: false,
          },
        },
      }
    });
  
    // Memuat plugin
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
  
    // Mendaftarkan tugas default
    grunt.registerTask('default', ['concat', 'uglify', 'watch']);
  
    // Mengisi sumber file concat secara dinamis
    grunt.config('concat.dist.src', function() {
      const fs = require('fs');
      const path = require('path');
      const baseDir = path.resolve(__dirname, '../../app/Views/Pages');
      let inputFiles = [];
  
      // Fungsi untuk secara rekursif mencari semua script.js dalam subfolder
      function readDirRecursive(directory) {
        fs.readdirSync(directory).forEach(file => {
          const fullPath = path.join(directory, file);
          if (fs.statSync(fullPath).isDirectory()) {
            readDirRecursive(fullPath);
          } else if (file === 'script.js') {
            inputFiles.push(fullPath);
          }
        });
      }
  
      readDirRecursive(baseDir);
  
      return inputFiles;
    }());
  };
  