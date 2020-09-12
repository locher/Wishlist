module.exports = function(grunt){
	
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		watch: {
			'default': {
				files: ['Gruntfile.js', 'assets/sass/*.scss', 'assets/sass/*/*.scss'],
				tasks: ['sass:dev', 'autoprefixer']
			},
			'svg': {
				files: ['Gruntfile.js', 'assets/img/svg-dev/*.svg', 'assets/img/svg-dev/sprite/*.svg'],
				tasks: ['svgmin', 'svgstore']
			}
		},
		sass: {
			options:{
				sourceMap: true,
				outFile: "assets/style.css",
			},
			dev: {
				files: {
					'assets/style.css': 'assets/sass/styles.scss',
				},
				options:{
					style: 'expanded',
					
				},
			},
		},
		autoprefixer: {
			options: {
				browsers: ['last 3 versions'],
				map: {
					annotation: 'style.css.map',
				}

			},
			prefix: {
				src: 'assets/style.css',
				dest: 'assets/style.css'
			},

		},
		svgmin: {
	        options: {
	            plugins: [
	                { removeViewBox: false },
	                { removeUselessStrokeAndFill: false }
	            ]
	        },
	        dist: {
	            files: [{
                    expand: true,
                    cwd: 'assets/img/svg-dev',
                    src: '*.svg',
                    dest: 'assets/img/svg-prod'
                }]
	        }
	    },
		svgstore: {
		    options: {
		      prefix : 'icon-',
		      svg: {
		        viewBox : '0 0 100 100',
		        xmlns: 'http://www.w3.org/2000/svg'
		      }
		    },
		    your_target: {
		      files:{
		      	'assets/img/svg-prod/sprite/svgs.svg' : ['assets/img/svg-dev/*.svg'],
		      },
		    },
		},
	});
	
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-sass');
	grunt.loadNpmTasks('grunt-autoprefixer');
	grunt.loadNpmTasks('grunt-svgmin');
	grunt.loadNpmTasks('grunt-svgstore');
}