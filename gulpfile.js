const del = require( 'del' ),
	  gulp = require( 'gulp' ),
	  csso = require( 'gulp-csso' ),
	  sass = require( 'gulp-sass' ),
	  terser = require( 'gulp-terser' );

gulp.task( 'styles', () => {
	return gulp.src( 'assets/sass/**/*.scss' )
		.pipe( sass().on( 'error', sass.logError ) )
		.pipe( csso() )
		.pipe( gulp.dest( 'dist/css/' ) );
} );

gulp.task( 'scripts', () => {
	return gulp.src( 'assets/js/**/*.js' )
		.pipe( terser() )
		.pipe( gulp.dest( 'dist/js' ) );
} );

gulp.task( 'clean:css', () => {
	return del( [
		'dist/css/style.css',
	] );
} );

gulp.task( 'clean:js', () => {
	return del( [
		'dist/js/main.js',
	] );
} );

gulp.task( 'clean', gulp.series( [ 'clean:css', 'clean:js' ] ) );
gulp.task( 'build', gulp.series( [ 'clean', 'scripts', 'styles' ] ) );

gulp.task( 'watch', () => {
	gulp.watch( 'assets/sass/**/*.scss', done => {
		gulp.series( [ 'clean:css', 'styles' ] )( done );
	} );

	gulp.watch( 'assets/js/**/*.js', done => {
		gulp.series( [ 'clean:js', 'scripts' ] )( done );
	} );
} );
