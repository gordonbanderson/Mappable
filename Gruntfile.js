module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        cssmin: {
            css: {
                src: 'css/mapField.css',
                dest: 'css/mapField.min.css'
            }
        },
        uglify: {
            js: {
                files : {
                    'dist/js/mappablegoogle.min.js' : [
                        'client/src/google/FullScreenControl.js',
                        'client/src/google/markerclusterer.js',
                        'client/src/google/maputil.js'
                    ],

                    'dist/admin/js/map-field.min.js' : [
                         'admin/src/js/map-field.js'
                    ]

                }
            },
        },
    });
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.registerTask('default', ['cssmin:css', 'uglify:js']);
};
