if(!anflexGA)var anflexGA=new Object();

/* tsi(id)
Toggle Setting Interface
supply html element id

id:html element id
[id]: show current information, but with no update interface
[id]_update: update interface
[id]_clink: button that toggles current info and update interface, 'change' or 'cancel'
*/

anflexGA.tsi=new Function("id","if(!id)return false;if(document.getElementById(id).style.display!='none'){document.getElementById(id).style.display='none';document.getElementById(id+'_update').style.display='inline';document.getElementById(id+'_clink').innerHTML='cancel';}else{document.getElementById(id).style.display='inline';document.getElementById(id+'_update').style.display='none';document.getElementById(id+'_clink').innerHTML='change';}");
