module.exports = function(grunt) {
    grunt.initConfig({
        autoprefixer: {
            dist: {
                files: {
                    'web/assets/css/style.css': 'web/assets/css/style.css'
                }
            }
        },
        watch: {
            css: {
                files: ['web/assets/css/**/*.less'],
                tasks: ['less', 'autoprefixer', 'csso']
            },
            js: {
                files: ['web/assets/js/**/*.js']
            }
        },
        less: {
            development: {
                options: {
                    paths: ["web/assets/css"]
                },
                files: {
                    "web/assets/css/style.css": "web/assets/css/main.less"
                }
            }
        },
        csso: {
            compress: {
                options: {
                    report: 'gzip'
                },
                files: {
                    'web/assets/css/style.min.css': ['web/assets/css/style.css']
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
