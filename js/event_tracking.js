if(!anflexGA) var anflexGA = new Object();

jQuery(document).ready(function($){

	/* prepare download/external link tracking for scanning all a links */
	jQuery("a").click(function(event){
		href = jQuery(this).attr("href");
		
		/* basic download and external link tracking
		Download Link tracking occurs when clicking URL link that has href matching file extension, regardless of link URL domain
		External Link occurs when clicking URL link that goes out of internal domain list
		When both Download and External Link criteria match, only Download Link occurs
		*/
		
		if(anflexGA.lt_d&&anflexGA.isDownload(anflexGA.download_extension_list,href)) {
			_gaq.push(['_trackEvent','Download Links','Downloaded',decodeURIComponent(href)]);
		} else if(anflexGA.findDomain(href)&&anflexGA.lt_e&&!anflexGA.isInternalLink(anflexGA.internal_domain_list,href)) {
			_gaq.push(['_trackEvent','External Links','Clicked',decodeURIComponent(href)]);
		}
	});
});

/* supporting functions */
anflexGA.findDomain = new Function("url","if(!url) return false;if(url.match(/^https?:/)) return url.split('/')[2];else return false;");
anflexGA.findExtension = new Function("url","if(!url) return false;patharray = url.split('?')[0].split('#')[0].split('/');ext=patharray[patharray.length-1].split('.')[1];if(ext) return ext; else return false;");
anflexGA.isDownload = new Function("csv","url","if(!url){return false;}var ext=anflexGA.findExtension(url);var extarray=csv.split(',');for(i in extarray){if(ext==extarray[i]){return true;}}return false;");
anflexGA.isInternalLink = new Function("csv","url","if(!url){return false;}var dom=anflexGA.findDomain(url);var domarray=csv.split(',');for(i in domarray){if(dom.indexOf(domarray[i])>-1){return true;}}return false;");
