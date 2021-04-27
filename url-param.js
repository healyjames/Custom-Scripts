<script>

/*

This script alters the href link on the button at the bottom of the page.

The purpose of this script is to pass the parameter from this page to the next page so that when the form is submitted, we can capture the referrer.

As there is no [easy] way to alter the PHP code so that the url parameter can be passed along, I have had to do it in Javascript.

The following code gets the current url, splits it at the '=' symbol and takes the String that follows that and stores is as a variable to append to the link.

If there is no parameter, this variable is automatically set to TVC.

The current URL is then split by "/". Each segment is looped through and concatenated to a new String. It does not take into account the final segment as this would hold the sup code. This new String is then used as the basis for the href link.

This link is then appended with the /apply/?sup=a and the parameter.

*/

var url = window.location.href;

var param = url.split('=')[1];

if(!param){ param = "TVC"; }

var link = url.split('/');/*.concat("/apply/=", param);*/

var updated = "";

for(var i = 0; i < link.length; i++){
    
    if(i != (link.length - 1)){
        
        updated += link[i] + "/";
        
    }
}


document.getElementById("target-link").setAttribute("href", updated.concat("apply/?sup=", param));


</script>