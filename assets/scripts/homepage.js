/*
 * --------------------------------------------------------------------
 * jQuery effects for footprinted.org homepage
 * Author: Jorge L. Zapico, jorge@zapi.co
 * Copyright (c) 2011 Jorge L. Zapico footprinted.org 
 * licensed under MIT 
 * --------------------------------------------------------------------
*/

(function($)
{
    $(".blue").click(function(event){
      $(this).hide();   
      // Stop the link click from doing its normal thing
      event.preventDefault();
    });
});