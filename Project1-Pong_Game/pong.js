// Student Name: Saurabh Singh | 1001568347

function initialize(){
	// Defined global variables here to be less verbose
	ball = document.getElementById("ball")
	paddle = document.getElementById("paddle")
	court = document.getElementById("court")
	courtBoundary = court.getBoundingClientRect()
	speedMultiplier = 2
	// Initialized the top and left style properties to zero, else modification would not have been possible
	ball.style.left = "0px"
	// Initialized the y position of ball to be random between the end positions of court which is hard coded below
	ball.style.top = Math.random() * (398 - 82) + 82 + "px";
	// Used the query selector method below to retrieve the checked radio button, as defined 
	// on https://stackoverflow.com/questions/3869535/how-to-get-the-selected-radio-button-value-using-js
	ballSpeed = (document.querySelector('input[name = "speed"]:checked').value + 1)*speedMultiplier
	ballSpeedX = 0
	ballSpeedY = 0
	document.getElementById("strikes").innerHTML = 0
}

function movePaddle(event){
	var y = event.pageY
	if(y>=courtBoundary.top && y<=courtBoundary.bottom-paddle.getBoundingClientRect().height){
		paddle.style.top = y-courtBoundary.top + "px"
	}
}

function startGame(){
	// Math.random() generates values between 0 and 1
	rand = Math.random()
	// To randomize the start angle, setting the y speed to either positive or negative of selected ball speed
	ballSpeedY = (rand >= 0.5) ? (-1*ballSpeed) : ballSpeed
	ballSpeedX = ballSpeed
	// console.log("rand: " + rand + ", ballSpeedY: " + ballSpeedY + ", ballSpeedX: " + ballSpeedX)
	// moveBallTimer is a global variable so as to stop the timer when required. window prefix can be ommitted also from setInterval method. 
	moveBallTimer = window.setInterval(moveBall, 20)
}

function moveBall(){
	// Calculate the new X and Y positions depending on current position and ball speed. If the new positions are valid then change the style
	// properties of the ball else rebound the ball in the direction for which the new coordinate position is not valid. 
	var newX = ball.getBoundingClientRect().left + ballSpeedX
	var newY = ball.getBoundingClientRect().top + ballSpeedY
	// Ball hits the right boundary of court
	if(newX >= courtBoundary.right-ball.width){
		if(parseInt(document.getElementById("strikes").innerHTML) > parseInt(document.getElementById("score").innerHTML)){
			document.getElementById("score").innerHTML = document.getElementById("strikes").innerHTML
		}
		window.clearInterval(moveBallTimer)
		initialize()
	}
	// Ball hits the paddle
	if(newX > paddle.getBoundingClientRect().left-ball.width && newY >= paddle.getBoundingClientRect().top && newY <= paddle.getBoundingClientRect().bottom){
		ballSpeedX = -1*ballSpeedX
		document.getElementById("strikes").innerHTML = parseInt(document.getElementById("strikes").innerHTML) + 1
	}
	// Ball hits the left boundary of court
	if(newX < courtBoundary.left){
		ballSpeedX = -1*ballSpeedX
	}
	// Ball hits the bottom boundary of court
	if(newY > courtBoundary.bottom-ball.height){
		ballSpeedY = -1*ballSpeedY
	}
	// Ball hits the top boundary of court
	if(newY < courtBoundary.top){
		ballSpeedY = -1*ballSpeedY
	}
	ball.style.left = parseInt(ball.style.left) + ballSpeedX + "px";
	ball.style.top = parseInt(ball.style.top) + ballSpeedY + "px";
}

function resetGame(){
	// This stops the repeated call to moveBall method
	window.clearInterval(moveBallTimer)
	// Only setting the strikes value to zero and not score, as specified the prof
	document.getElementById("strikes").innerHTML = 0
	// document.getElementById("score").innerHTML = 0
	initialize()
}

function setSpeed(speed){
	// Multiplied the input speed with 2 as with the default values the ball moves very slowly
	ballSpeed = (speed + 1)*speedMultiplier
}