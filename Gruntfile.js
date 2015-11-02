module.exports = function(grunt){
	
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		watch: {
			'default': {
				files: ['Gruntfile.js', 'sass/*.scss', 'img/svg-dev/*.svg'],
				tasks: ['sass:dev', 'autoprefixer']
			}
		},
		sass: {
			options:{
				sourceMap: true,
				outFile: "style.css",
			},
			dev: {
				files: {
					'style.css': 'sass/styles.scss',
				},
				options:{
					style: 'expanded',
				},
			},
		},
		autoprefixer: {
			options: {
				browsers: ['last 3 versions', 'Android >= 2.1', 'iOS >= 7'],
				map: {
					annotation: 'style.css.map',
				}
				
			},
			prefix: {
				src: 'style.css',
				dest: 'style.css'
			},

		},
	});
	
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-sass');
	grunt.loadNpmTasks('grunt-autoprefixer');
}