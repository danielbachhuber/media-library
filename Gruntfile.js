module.exports = function( grunt ) {
	grunt.initConfig({

		pkg: grunt.file.readJSON('package.json'),

		sass: {
			core: {
				files: {
					'assets/stylesheets/core.css': 'assets/stylesheets/sass/core.scss'
				}
			}
		},

		watch: {
			sass: {
				files: ['assets/stylesheets/sass/*.scss'],
				tasks: ['sass'],
			},
			livereload: {
				options: { livereload: true },
				files: [
					'assets/stylesheets/*.css'
				],
			}
		}
	});

	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-sass' );

	grunt.registerTask( 'build', ['sass'] );
	grunt.registerTask( 'default', ['build'] );
};