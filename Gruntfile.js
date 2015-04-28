module.exports = function(grunt) {
    grunt.initConfig({
        autoprefixer: {
            dist: {
                files: {
                    'web/assets/css/style.css': 'web/assets/css/style.css',
                    'web/css/style.css': 'web/css/style.css'
                }
            }
        },
        watch: {
            css: {
                files: ['web/assets/css/**/*.less', 'web/css/**/*.less'],
                tasks: ['less', 'autoprefixer', 'csso']
            }
        },
        less: {
            development: {
                options: {
                    paths: ["web/assets/css", "web/css"]
                },
                files: {
                    "web/assets/css/style.css": "web/assets/css/main.less",
                    "web/css/style.css": "web/css/main.less"
                }
            }
        },
        csso: {
            compress: {
                options: {
                    report: 'gzip'
                },
                files: {
                    'web/assets/css/style.min.css': ['web/assets/css/style.css'],
                    'web/css/style.min.css': ['web/css/style.css']
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-autoprefixer');
    grunt.loadNpmTasks('grunt-csso');

    grunt.registerTask('default', ['watch']);
};
