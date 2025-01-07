<?php
echo "
.nav-pills .nav-link.active{
    background-color: #042ba314;
}

nav{
    background-color: #333;
    margin-bottom: 2em;
}

nav li{
    display: inline-block;
}

nav li a{
    color: #fff;
    text-decoration: none;
    padding: 15px;
    display: inline-block;
    transition: all 0.5s;
}

nav li a:hover{
    background-color: #042ba314;
}

.dropdown-menu{
    position: absolute;
    display: none;
}

.dropdown-menu a{
    display: block;
}

.dropdown:hover .dropdown-menu{
    display: block;
    margin-top: 2px;
    transition: all 0.7s ease-in-out;
}";
?>