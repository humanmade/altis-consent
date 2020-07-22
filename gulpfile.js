const gulp = require( 'gulp' ),
	  sass = require( 'gulp-sass' ),
	  del = require( 'del' );

gulp.task( 'styles', () => {
	return gulp.src( 'assets/sass/**/*.scss' )
		.pipe( sass().on( 'error', sass.logError ) )
		.pipe( gulp.dest( 'assets/css/' ) );
} );

gulp.task( 'clean', () => {
	return del( [
		'assets/css/style.css',
	] );
} );

gulp.task( 'build', gulp.series( [ 'clean', 'styles' ] ) );

gulp.task( 'watch', () => {
	gulp.watch( 'assets/sass/**/*.scss', ( done ) => {
		gulp.series( [ 'clean', 'styles' ] )( done );
	} );
} );
