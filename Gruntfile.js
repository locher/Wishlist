module.exports = function(grunt){
	
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		watch: {
			'default': {
				files: ['Gruntfile.js', 'src/sass/*.scss', 'src/sass/*/*.scss'],
				tasks: ['sass:dev', 'autoprefixer']
			},
			'svg': {
				files: ['Gruntfile.js', 'src/img/svg-dev/*.svg', 'src/img/svg-dev/sprite/*.svg'],
				tasks: ['svgmin', 'svgstore']
			}
		},
		sass: {
			options:{
				sourceMap: true,
				outFile: "src/style.css",
			},
			dev: {
				files: {
					'src/style.css': 'src/sass/styles.scss',
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
					annotation: 'src/style.css.map',
				}
				
			},
			prefix: {
				src: 'src/style.css',
				dest: 'src/style.css'
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
                    cwd: 'src/img/svg-dev',
                    src: '*.svg',
                    dest: 'src/img/svg-prod'
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
		      	'img/svg-prod/sprite/svgs.svg' : ['src/img/svg-dev/*.svg'],
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