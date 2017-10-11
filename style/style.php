<?php header("Content-type: text/css");?>

.keyPad {
    margin-top: 80px;
    margin-left: auto;
    margin-right: auto;
    width: 70%;
    float: right;
}

#keyPad {
    width: 160px;
}

#keyPad input {
	border: 1px solid #000;
	width: 50px;
	height: 40px;
	border-radius: 5px;
	background-color: rgb(167, 163, 163);
	box-shadow: 3px 3px 3px #c9c9c9 inset;
}

#keyPad :hover {
	background-color: rgb(111, 108, 108);
	box-shadow: 3px 3px 3px #353535 inset;
}

.reset input {
    margin-top: 40px;
    display: inline-block;
    border: 1px solid #000;
	width: 160px;
	height: 40px;
	border-radius: 5px;
	background-color: rgb(167, 163, 163);
	box-shadow: 3px 3px 3px #c9c9c9 inset;
}

.reset :hover {
    background-color: rgb(111, 108, 108);
	box-shadow: 3px 3px 3px #353535 inset;
}

.gameUp {
	margin-left: 55px;
	margin-right: 55px;
}

.gameLeft {
	display: inline;
}

.gameDown {
	display: inline;
}

.gameRight {
	display: inline;
}

.content {
	height: 320px;
	width: 320px;
	margin-top: 20px;
    border: 1px solid #73AD21;
}

#dino {
	position: absolute;
	z-index:10;
	width:32px;
	height:32px;
	background-image:url(../img/Stegosaurus.png);
	background-repeat:no-repeat;
}

.left {
	-o-transform: rotateY(180deg);
	-ms-transform: rotateY(180deg);
	-webkit-transform: rotateY(180deg);
	-moz-transform: rotateY(180deg);
	transform: rotateY(180deg);
}

.tile, .t {
	width: 32px;
	height: 32px;
	float: left;
	background-repeat: no-repeat;
}

.t10 {
	background: url(../img/tiles/grass0.png);
}
.t11 {
	background: url(../img/tiles/dngn_metal_wall.png) -32px -96px;
}
.t12 {
	background: url(../img/tiles/crate.png) 0 -128px;
}
.t13 {
	background: url(../img/tiles/dngn_closed_door.png) -64px -128px;
}
.t14 {
	background: url(../img/tiles/floor_sand_stone0.png) -32px -32px;
}
