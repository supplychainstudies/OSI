<?php
class Bibliographymodel extends FT_Model{
    function Bibliographymodel(){
        parent::__construct();
    }

	public function convertBibliography($dataset){
		$converted_dataset = array();
		foreach ($dataset as $key=>$record) {
			if (isset($record[$this->arc_config['ns']['dc']."title"]) == true) {
				foreach($record[$this->arc_config['ns']['dc']."title"] as $title) {
					$converted_dataset[$key]['title'] = $title;
				}
			} else {
				$converted_dataset[$key]['title'] = "";
			}
			if (isset($record[$this->arc_config['ns']['bibo']."authorList"]) == true) {
				$person_array = array();
				foreach($record[$this->arc_config['ns']['bibo']."authorList"] as $author_uri) {
					$person = $this->getTriples($author_uri);
					foreach ($person[$this->arc_config['ns']['foaf'].'firstName'] as $firstName) {
						$person_array['firstName'] = $firstName;
					} 
					foreach ($person[$this->arc_config['ns']['foaf'].'lastName'] as $lastName) {
						$person_array['lastName'] = $lastName;
					}
					$converted_dataset[$key]['authors'][] = $person_array;						
				}
				
			} elseif (isset($record[$this->arc_config['ns']['dcterms']."creator"]) == true)  {
				foreach($record[$this->arc_config['ns']['dcterms']."creator"] as $author_uri) {
					$person = $this->getTriples($author_uri);
					foreach ($person[$this->arc_config['ns']['foaf'].'firstName'] as $firstName) {
						$person_array['firstName'] = $firstName;
					} 
					foreach ($person[$this->arc_config['ns']['foaf'].'lastName'] as $lastName) {
						$person_array['lastName'] = $lastName;
					}
					$converted_dataset[$key]['authors'][] = $person_array;						
				}
			}
			if (isset($record[$this->arc_config['ns']['bibo']."uri"]) == true) {
				foreach($record[$this->arc_config['ns']['bibo']."uri"] as $uri) {
					$converted_dataset[$key]['uri'] = $uri;
				}
			} else {
				$converted_dataset[$key]['uri'] = "";
			} 
			if (isset($record[$this->arc_config['ns']['dc']."date"]) == true) {
				foreach($record[$this->arc_config['ns']['dc']."date"] as $date) {
					$converted_dataset[$key]['date'] = $date;
				}
			} else {
				$converted_dataset[$key]['date'] = "";
			}
			/*
			"dc:creator" => $organization_uris,
			"bibo:isbn" => trim($line_array[5]),
			"bibo:volume" => trim($line_array[6]),
			"bibo:issue" => trim($line_array[7]),
			"bibo:doi" => trim($line_array[10]),
			"bibo:chapter" => trim($line_array[13]),
			"bibo:locator" => trim($line_array[14]),	
			*/					
		}
		return $converted_dataset;
	}
		
	public function getBibliography($URI) {
		$q = "select ?bibouri where { " . 
			" <".$URI."> eco:hasDataSource ?bibouri . " .			
			"}";				
		$records = $this->executeQuery($q);
		$full_record = array();		
		foreach ($records as $record) {
			$link = array('link' => $record['bibouri']);
			$full_record[$record['bibouri']] = array_merge($link, $this->getTriples($record['bibouri']));			
		}
		return $full_record;
	}
	
	public function cite_APA($datasets) {
		$refs = array();
		foreach ($datasets as $dataset) {
			$cite_string = "";
			if (isset($dataset['authors']) == true) {
				foreach ($dataset['authors'] as $author) {
					$cite_string .= $author['lastName'].", ". substr($author['firstName'], 0, 1)."., ";
				}
				$cite_string = substr(trim($cite_string),0,strlen($cite_string)-1);
			}
			if (isset($dataset['title']) == true) {
				$cite_string .= " ".trim($dataset['title']).".";
			}
			if (isset($dataset['journal']) == true) {
				$cite_string .= " ".trim($dataset['journal']).", ";
			}
			if (isset($dataset['volume']) == true) {
				$cite_string .= trim($dataset['volume']);
			}
			if (isset($dataset['issue']) == true) {
				$cite_string .= "(".trim($dataset['issue'])."), ";
			}
			$dash = "";
			if (isset($dataset['pageStart']) == true) {
				$cite_string .= trim($dataset['pageStart']);
				$dash = "-";
			}
			if (isset($dataset['pageEnd']) == true) {
				$cite_string .= $dash.trim($dataset['pageStart']);			
			}
			if (substr(trim($cite_string),strlen($cite_string)-1,strlen($cite_string)) == ",") {
				$cite_string = substr(trim($cite_string),0,strlen($cite_string)-1);
			}
			$cite_string .= ".";
			if (isset($dataset['doi']) == true) {
				$cite_string .= trim($dataset['doi']);			
			}	
			/*
			if (isset($dataset['uri']) == true) {
				foreach ($dataset['uri'] as $uri) {
					$cite_string .= ", from ".trim($uri);
				}			
			}*/
			$refs[] = $cite_string;
		}	
		return $refs;
	}
	/* 
	
	
	
	
	
	
	Examples:

	Articles in journals, magazines, and newspapers

	References to periodical articles must include the following elements: author(s), date of publication, article title, journal title, volume number, issue number (if applicable), and page numbers.

	Journal article, one author, accessed online

	Ku, G. (2008). Learning to de-escalate: The effects of regret in escalation of commitment. Organizational Behavior and Human Decision Processes, 105(2), 221-232. doi:10.1016/j.obhdp.2007.08.002


	Journal article, two authors, accessed online

	Sanchez, D., & King-Toler, E. (2007). Addressing disparities consultation and outreach strategies for university settings. Consulting Psychology Journal: Practice and Research, 59(4), 286-295. doi:10.1037/1065- 9293.59.4.286


	Journal article, more than two authors, accessed online

	Van Vugt, M., Hogan, R., & Kaiser, R. B. (2008). Leadership, followership, and evolution: Some lessons from the past. American Psychologist, 63(3), 182-196. doi:10.1037/0003-066X.63.3.182


	Article from an Internet-only journal

	Hirtle, P. B. (2008, July-August). Copyright renewal, copyright restoration, and the difficulty of determining copyright status. D-Lib Magazine, 14(7/8). doi:10.1045/july2008-hirtle


	Journal article from a subscription database (no DOI)

	Colvin, G. (2008, July 21). Information worth billions. Fortune, 158(2), 73-79. Retrieved from Business Source Complete, EBSCO. Retrieved from http://search.ebscohost.com


	Magazine article, in print

	Kluger, J. (2008, January 28). Why we love. Time, 171(4), 54-60.



	Newspaper article, no author, in print

	As prices surge, Thailand pitches OPEC-style rice cartel. (2008, May 5). The Wall Street Journal, p. A9.


	Newspaper article, multiple authors, discontinuous pages, in print

	Delaney, K. J., Karnitschnig, M., & Guth, R. A. (2008, May 5). Microsoft ends pursuit of Yahoo, reassesses its online options. The Wall Street Journal, pp. A1, A12.


	Books

	References to an entire book must include the following elements: author(s) or editor(s), date of publication, title, place of publication, and the name of the publisher.

	No Author or editor, in print

	Merriam-Webster's collegiate dictionary (11th ed.). (2003). Springfield, MA: Merriam- Webster.


	One author, in print

	Kidder, T. (1981). The soul of a new machine. Boston: Little, Brown & Company.



	Two authors, in print

	Frank, R. H., & Bernanke, B. (2007). Principles of macro-economics (3rd ed.). Boston: McGraw-Hill/Irwin.


	Corporate author, author as publisher, accessed online

	Australian Bureau of Statistics. (2000). Tasmanian year book 2000 (No. 1301.6). Canberra, Australian Capital Territory: Author. Retrieved from http://www.ausstats.abs.gov.au/ausstats/subscriber.nsf/0/CA2568710006989... $File/13016_2000.pdf


	Edited book

	Gibbs, J. T., & Huang, L. N. (Eds.). (2001). Children of color: Psychological interventions with culturally diverse youth. San Francisco: Jossey-Bass.


	Dissertations

	References for dissertations should include the following elements: author, date of publication, title, and institution (if you accessed the manuscript copy from the university collections). If there is a UMI number or a database accession number, include it at the end of the citation.



	Dissertation, accessed online

	Young, R. F. (2007). Crossing boundaries in urban ecology: Pathways to sustainable cities (Doctoral dissertation). Available from ProQuest Dissertations & Theses database. (UMI No. 327681)


	Essays or chapters in edited books

	References to an essay or chapter in an edited book must include the following elements: essay or chapter authors, date of publication, essay or chapter title, book editor(s), book title, essay or chapter page numbers, place of publication, and the name of the publisher.

	One author

	Labajo, J. (2003). Body and voice: The construction of gender in flamenco. In T. Magrini (Ed.), Music and gender: perspectives from the Mediterranean (pp. 67-86). Chicago: University of Chicago Press.


	Two editors

	Hammond, K. R., & Adelman, L. (1986). Science, values, and human judgment. In H. R. Arkes & K. R. Hammond (Eds.), Judgement and decision making: An interdisciplinary reader (pp. 127-143). Cambridge: Cambridge University Press.


	Encyclopedias or dictionaries and entries in an encyclopedia

	References for encyclopedias must include the following elements: author(s) or editor(s), date of publication, title, place of publication, and the name of the publisher. For sources accessed online, include the retrieval date as the entry may be edited over time.



	Encyclopedia set or dictionary

	Sadie, S., & Tyrrell, J. (Eds.). (2002). The new Grove dictionary of music and musicians (2nd ed., Vols. 1-29). New York: Grove.


	Article from an online encyclopedia

	Containerization. (2008). In Encyclopædia Britannica. Retrieved May 6, 2008, from http://search.eb.com


	Encyclopedia article

	Kinni, T. B. (2004). Disney, Walt (1901-1966): Founder of the Walt Disney Company. In Encyclopedia of Leadership (Vol. 1, pp. 345-349). Thousand Oaks, CA: Sage Publications.


	Research reports and papers

	References to a report must include the following elements: author(s), date of publication, title, place of publication, and name of publisher. If the issuing organization assigned a number (e.g., report number, contract number, or monograph number) to the report, give that number in parentheses immediately after the title. If it was accessed online, include the URL.



	Government report, accessed online

	U.S. Department of Health and Human Services. (2005). Medicaid drug price comparisons: Average manufacturer price to published prices (OIG publication No. OEI-05-05- 00240). Washington, DC: Author. Retrieved from http://www.oig.hhs.gov/oei/reports/oei-05-05-00240.pdf


	Government reports, GPO publisher, accessed online

	Congressional Budget Office. (2008). Effects of gasoline prices on driving behavior and vehicle markets: A CBO study (CBO Publication No. 2883). Washington, DC: U.S. Government Printing Office. Retrieved from http://www.cbo.gov/ftpdocs/88xx/doc8893/01-14-GasolinePrices.pdf


	Technical and/or research reports, accessed online

	Deming, D., & Dynarski, S. (2008). The lengthening of childhood (NBER Working Paper 14124). Cambridge, MA: National Bureau of Economic Research. Retrieved July 21, 2008, from http://www.nber.org/papers/w14124


	Document available on university program or department site

	Victor, N. M. (2008). Gazprom: Gas giant under strain. Retrieved from Stanford University, Program on Energy and Sustainable Development Web site: http://pesd.stanford.edu/publications/gazprom_gas_giant_under_strain/


	Audio-visual media

	References to audio-visual media must include the following elements: name and function of the primary contributors (e.g., producer, director), date, title, the medium in brackets, location or place of production, and name of the distributor. If the medium is indicated as part of the retrieval ID, brackets are not needed.

	Videocassette/DVD

	Achbar, M. (Director/Producer), Abbott, J. (Director), Bakan, J. (Writer), & Simpson, B. (Producer) (2004). The corporation [DVD]. Canada: Big Picture Media Corporation.


	Audio recording

	Nhat Hanh, T. (Speaker). (1998). Mindful living: a collection of teachings on love, mindfulness, and meditation [Cassette Recording]. Boulder, CO: Sounds True Audio.


	Motion picture

	Gilbert, B. (Producer), & Higgins, C. (Screenwriter/Director). (1980). Nine to five [Motion Picture]. United States: Twentieth Century Fox.


	Television broadcast

	Anderson, R., & Morgan, C. (Producers). (2008, June 20). 60 Minutes [Television broadcast]. Washington, DC: CBS News.


	Television show from a series

	Whedon, J. (Director/Writer). (1999, December 14). Hush [Television series episode]. In Whedon, J., Berman, G., Gallin, S., Kuzui, F., & Kuzui, K. (Executive Producers), Buffy the Vampire Slayer. Burbank, CA: Warner Bros..


	Music recording

	Jackson, M. (1982). Beat it. On Thriller [CD]. New York: Sony Music.


	Undated Web site content, blogs, and data

	For content that does not easily fit into categories such as journal papers, books, and reports, keep in mind the goal of a citation is to give the reader a clear path to the source material. For electronic and online materials, include stable URL or database name. Include the author, title, and date published when available. For undated materials, include the date the resource was accessed.



	Blog entry

	Arrington, M. (2008, August 5). The viral video guy gets $1 million in funding. Message posted to http://www.techcrunch.com


	Professional Web site

	National Renewable Energy Laboratory. (2008). Biofuels. Retrieved May 6, 2008, from http://www.nrel.gov/learning/re_biofuels.html


	Data set from a database

	Bloomberg L.P. (2008). Return on capital for Hewitt Packard 12/31/90 to 09/30/08. Retrieved Dec. 3, 2008, from Bloomberg database.
	Central Statistics Office of the Republic of Botswana. (2008). Gross domestic product per capita 06/01/1994 to 06/01/2008 [statistics]. Available from CEIC Data database.


	Entire Web site
	When citing an entire Web site (and not a specific document on that site), no Reference List entry is required if the address for the site is cited in the text of your paper.

	Witchcraft In Europe and America is a site that presents the full text of many essential works in the literature of witchcraft and demonology (http://www.witchcraft.psmedia.com/).
	*/
		

} // End Class
