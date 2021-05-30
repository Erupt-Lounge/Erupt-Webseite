// Burger

const burger = document.getElementsByClassName( "burger" )[0];

burger.onclick = function() {
	let nav = document.getElementsByClassName( "nav-points" )[0];
	if( burger.classList.contains( "closed" ) ) {
		burger.classList.remove( "closed" );
		burger.classList.add( "open" );
		nav.classList.add( "show" );
	} else {
		burger.classList.remove( "open" );
		burger.classList.add( "closed" );
		nav.classList.remove( "show" );
	}
};

// Landing header animation
const signet = document.getElementById('signet');
const lounge = document.getElementById('cat-one');
const gaming = document.getElementById('cat-two');
if( lounge ) {
	lounge.addEventListener( 'mouseover', function() {
		lounge.style.width = '60%';
		gaming.style.width = '40%';
		signet.style.transform = 'translate(-20%,-50%)';
	});
	lounge.addEventListener( 'mouseout', function() {
		lounge.style.width = '50%';
		gaming.style.width = '50%';
		signet.style.transform = 'translate(-50%,-50%)';
	});
}
if( gaming ) {
	gaming.addEventListener( 'mouseover', function() {
		gaming.style.width = '60%';
		lounge.style.width = '40%';
		signet.style.transform = 'translate(-80%,-50%)';
	});
	gaming.addEventListener( 'mouseout', function() {
		lounge.style.width = '50%';
		gaming.style.width = '50%';
		signet.style.transform = 'translate(-50%,-50%)';
	});
}