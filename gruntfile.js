/**
 * add grunt watch: 'crontab -e: @reboot cd /www/yoda/resources/assets/grunt/ && grunt watch &'
 */

module.exports = function(grunt) {
    require('time-grunt')(grunt);
    require('jit-grunt')(grunt);

// folders variables
// ==================================================== 

    var assetsDir = './resources/assets/';
    var jsDir     = assetsDir + 'js/';
    var lessDir   = assetsDir + 'less/';
    var publicDir = './public/';
    var buildDir  = publicDir + 'build/';

    DEBUG = true;

// project task definitions
// ==================================================== 
    grunt.initConfig({

// css: preprocessing, concatenation & minification
// -------------------------------------------------------------
        less: { 
            options: {
                compress: !DEBUG,
            },
            app: {
                dest: publicDir + 'css/app.css',
                src : [
                    lessDir + '/yeb/yeb.less',
                    lessDir + '/yoda/yoda.less',
                ],
            },
            vendor: {
                dest: publicDir+'css/vendor.css',
                src : [
                    lessDir + '/vendor/*.*',
                    lessDir + '/vendor/datatable/datatable.less',
                ],
            },
            // themes
            adminlte: {
                dest: publicDir+'css/adminlte.css',
                src : lessDir + 'themes/adminlte/adminlte.css',
            },
        },

// js: vendor & other: concat & minify
// -------------------------------------------------------------
        concat: { 
            // vendor files
            vendor: {
                dest: publicDir + 'js/vendor.js',
                src: [
                    '/vanilla/*.js', 
                    '/jquery/*.js',
                    '/bootstrap/*.js', 
                    '/datatables/dataTables.js',
                    '/datatables/plugin.*.js',
                    '/datatables/dataTables.bootstrap.js',
                    '/datatables/dataTables.responsive.js',
                ].map(function(f) { return jsDir + '/vendor/'+f; }),
            },
            // themes
            adminlte: { 
                dest: publicDir+'js/adminlte.js', 
                src : jsDir+'themes/adminlte.js' 
            },
            // other
            debug   : { 
                dest: publicDir+'js/debug.js', 
                src : jsDir+'other/debug/*.js' 
            },
            snippets: { 
                dest: publicDir+'js/snippets.js', 
                src : jsDir+'other/snippets/*.js' 
            },
            lang_fr : { 
                dest: publicDir+'js/lang_fr.js', 
                src : jsDir+'other/langs/fr/*.js'
            },
        },

// js: app: browserify, babel & bundling
// -------------------------------------------------------------
        gulp: {
            app: function(){ 
                return es6files({
                    entry : jsDir + '/Yoda/Routes.js',
                    dest  : publicDir + 'js',
                    rename: 'app.js',
                });
            }
        },

// js: uglify - build only
// -------------------------------------------------------------
        uglify: {
            options: {
                mangle: false,
            },
            all: {
                files: [{
                    cwd: publicDir,
                    src: '/**/*.js',
                    dest: publicDir
                }],
            }
        },

// all: cache busting
// -------------------------------------------------------------
        hash: {
            options: {
                mapping      : buildDir + 'rev-manifest.json',
                srcBasePath  : publicDir,
                destBasePath : buildDir,
                hashSeparator: '-'
            },
            js: {
                src : publicDir + 'js/*.js',
                dest: buildDir + 'js',
            },
            css: {
                src : publicDir + 'css/*.css',
                dest: buildDir + 'css',
            }
        },

// all: watch + concurrent (cli)
// -------------------------------------------------------------
        watch: {
            jsApp: {
                tasks: ['gulp', 'hash:js'],
                files: [ 
                    jsDir+'/Yeb/**/*.js',
                    jsDir+'/Yoda/**/*.js',
                ], 
            },
            jsOther: {
                tasks: ['newer:concat', 'hash:js'],
                files: [ 
                    jsDir+'/vendor/**/*.js',
                    jsDir+'/other/**/*.js',
                ], 
            },
            css: {
                tasks: ['newer:less', 'hash:css'],
                files: [ 
                    lessDir + '/**/*.*'
                ], 
                options: {
                    // livereload: 35721
                },
            },
        },

        concurrent: {
            css: ['less'],
            js : ['newer:concat', 'gulp'],
        },

    });
        
// register tasks
// ==================================================== 

    grunt.registerTask('default', [
        'concurrent', 
        'hash',
    ]);
    
    grunt.registerTask('build', [
        'concurrent',
        'uglify',
        'hash',
    ]);
    
};

// external helpers
// ==================================================== 
var es6files = function(params) {
    var 
        babelify   = require('babelify'),
        browserify = require('browserify'),
        gulp       = require('gulp'),
        transform  = require('vinyl-transform'),
        through2   = require('through2');

    return gulp
        .src(params.entry)
        .pipe(through2.obj(function (file, enc, next) {
            browserify(file.path)
            .transform(babelify)
            .bundle(function (err, res) {
                if (err) { return next(err); }
                file.contents = res;
                next(null, file);
            });
        }))
        .pipe(require('gulp-rename')(params.rename))
        .pipe(gulp.dest(params.dest));
};
