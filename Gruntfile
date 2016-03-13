/*global module:false*/
module.exports = function(grunt) {

    // Project configuration.
    grunt.initConfig({
        // Task configuration.
        phpunit: {
            classes: {
                dir: 'tests'

            },
            options: {
                bin: 'vendor/bin/phpunit',
                colors: true,
                coverage: false,
                configuration: 'phpunit.xml'
            }
        },
        watch: {
            scripts: {
                files: [
                    'src/**/*.php',
                    'tests/**/*.php'
                ],
                tasks: ['phpunit'],
                options: {
                    interrupt: true
                }
            }
        }
    });

    // These plugins provide necessary tasks.
    grunt.loadNpmTasks('grunt-phpunit');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // Default task.
    grunt.registerTask('default', ['watch']);

};
