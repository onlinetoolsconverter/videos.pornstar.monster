<div class="container">
        <h1 class="notranslate">Translation Panel</h1>
        <div class="form-group">
            <label for="title" class="notranslate">Title (English)</label>
            <input type="text" id="title" required>
        </div>
		
		<div class="form-group">
            <label for="meta-description" class="notranslate">Meta Description (English)</label>
            <textarea id="meta-description" rows="4" required></textarea>
			<span id="char-count" class="notranslate"></span>
			<button onclick="writeMetaDescriptionAI()" class="notranslate">Write Meta Description (AI)</button>
        </div>
		
        <div class="form-group">
            <label for="description" class="notranslate">Description (English)</label>
            <textarea id="description" rows="5" required></textarea>
			<button onclick="writeDescriptionAI()" class="notranslate">Write Description (AI)</button>
        </div>
		
		<div class="form-group">
            <label for="description" class="notranslate">Tags (English)</label>
            <input type="text" id="tags" rows="5" required>
        </div>
		
        <button onclick="translateAndSave()" class="notranslate">Translate and Save to JSON</button>
		
        <div id="google_translate_element" class="hidden"></div>
		
        <div id="translationFields" class="translation-fields1"></div>
		
		
		<br/><br/>
        <a href="#" title="This field is require, so that translation engine can find something to translate and display. Do not add notranslate class" class="notranslate">Translation Done.</a>
        <span id="progress" class="notranslate" style="color:green;"></span>
   </div>
   
    <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>
	
	<script>
	//global variable
	const translations = {};
	
	let title = '';
	
	
	const languages = [	         
            "hi", "bn", "es", "ru", "de", "fr", "ja", "pt", "tr", "it", "fa", 
			"zh-CN", "iw",
            "nl", "pl",  "vi", "id", "cs", "ko", "uk", "ar", "el",  
            "sv", "ro", "hu", "th", "da", "sk", "fi", "sr", "no", "bg", "lt", 
            "sl", "ca", "et", "lv",  "hr",
			"as", "gu", "ml", "ne", "mr", "pa", "ta", "ka", "te", "or", "ur", "ms"
        ];
		
		
	const languagesNumber = languages.length;
	console.log('languagesNumber:', languagesNumber);
		
		const languageCodes = {
		  "hi": "Hindi",
		  "bn": "Bengali",
		  "es": "Spanish",
		  "ru": "Russian",
		  "de": "German",
		  "fr": "French",
		  "ja": "Japanese",
		  "pt": "Portuguese",
		  "tr": "Turkish",
		  "it": "Italian",
		  "fa": "Persian-Farsi",
		  "nl": "Dutch",
		  "pl": "Polish",
		  "zh-CN": "Chinese",
		  "vi": "Vietnamese",
		  "id": "Indonesian",
		  "cs": "Czech",
		  "ko": "Korean",
		  "uk": "Ukrainian",
		  "ar": "Arabic",
		  "el": "Greek",
		  "iw": "Hebrew",
		  "sv": "Swedish",
		  "ro": "Romanian",
		  "hu": "Hungarian",
		  "th": "Thai",
		  "da": "Danish",
		  "sk": "Slovak",
		  "fi": "Finnish",
		  "sr": "Serbian",
		  "no": "Norwegian",
		  "bg": "Bulgarian",
		  "lt": "Lithuanian",
		  "sl": "Slovenian",
		  "ca": "Catalan",
		  "et": "Estonian",
		  "lv": "Latvian",
		  "hr": "Croatian",
		  "as" : "Assamese",
		  "gu" : "Gujarati",
		  "ml" : "Malayalam",
          "ne" : "Nepali",
		  "mr" : "Marathi",
		  "pa" : "Punjabi",
		  "ta" : "Tamil",
		  "ka" : "Kannada",
		  "te" : "Telugu",
		  "or" : "Oriya",
		  "ur" : "Urdu",
		  "ms" : "malay"
		};
	
	
       (function() {
			var originalXHR = window.XMLHttpRequest;

			function newXHR() {
				var realXHR = new originalXHR();
				var originalOpen = realXHR.open;
				var originalSend = realXHR.send;
				var requestData = null;

				realXHR.open = function(method, url, async, user, password) {
					this._url = url;
					this._method = method;
					return originalOpen.apply(this, arguments);
				};

				realXHR.send = function(data) {
					requestData = data;
					this.addEventListener('readystatechange', function() {
						if (this.readyState === 4) {
							try {
								
								
								
								// Parse the request data to extract the "bn" field
								var requestJson = JSON.parse(requestData);
								var languageField = requestJson[0][2];
								console.log('Request language:', languageField);

								// Parse the response data to extract the first four fields
								var responseJson = JSON.parse(this.responseText);
								
								/*not taking results by sequence anymore*/
								/*var titleTranslated = responseJson[0][0];
								var metaDescriptionTranslated = responseJson[0][1];
								var descriptionTranslated = responseJson[0][2];
								var tagsTranslated = responseJson[0][3];*/

								
								for (const element of responseJson[0]) {//console.log(element);
									if (element.startsWith("@#$")) {
									  titleTranslated = element.substring(3); // Remove "@#$" prefix
									} else if (element.startsWith("@$#")) {
									  metaDescriptionTranslated = element.substring(3); // Remove "@$#" prefix
									} else if (element.startsWith("$#@") || element.startsWith("<a i=0>$#@")) {
										
									  let removePattern = /<a i=\d+>/g; // Pattern to match <a i=number>
                                      let replacePattern = /<\/a>/g;    // Pattern to match </a>
									  // Remove <a i=number> tags
                                      descriptionTranslated = element.replace(removePattern, '');
									  
									  // Replace </a> with <br/>
                                      descriptionTranslated = descriptionTranslated.replace(replacePattern, '<br/><br/>');
									  
									  descriptionTranslated = descriptionTranslated.substring(3); // Remove "$#@" prefix
									  
									  
									} else if (element.startsWith("#$@")) {
									  tagsTranslated = element.substring(3); // Remove "#$@" prefix
									  
									  //replace bullshit chars from tags (sometime translate returns those)
									  tagsTranslated = tagsTranslated.replace(/[;.\?!@#\$%\^&\*\(\)=\+\[\]\{\}\\|\":'<>\/`~]/g, '');
									} else {
									  // Handle unexpected elements (if any)
									  console.warn("Unexpected element:", element);
									}
								}
								
								//console.log('Response title:', titleTranslated);
								//console.log('Response metaDescription:', metaDescriptionTranslated);
								//console.log('Response description:', descriptionTranslated);
								//console.log('Response tags:', tagsTranslated);
								
								translations[languageField] = {
									title: titleTranslated,
									metaDescription: metaDescriptionTranslated,
									description: descriptionTranslated,
									tags: tagsTranslated
								};
								
								
								let translationsNumber  = Object.keys(translations).length;
								
								console.log('Translation Done:', translationsNumber);
								
								const videoID = document.getElementById('id').value;
								
								//whatever we got just save it
								saveToFile(translations, videoID, allDone=false);
								
								//if translationsNumber is equal to languagesNumber plus 1 
								//for the default english language that already added
								if(translationsNumber == (languagesNumber + 1) ){
								   saveToFile(translations, videoID, allDone=true);
								}
					
							} catch (e) {
								console.error('Error parsing request or response data:', e);
							}
						}
					});
					return originalSend.apply(this, arguments);
				};

				return realXHR;
			}

			window.XMLHttpRequest = newXHR;
		})();


    
	


        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en'
            }, 'google_translate_element');
        }

        function createTranslationFields(title, metaDescription, description, tags) {
            const translationFields = document.getElementById('translationFields');
            translationFields.innerHTML = '';

            /*when creating fields for translations 
			i am inserting char sets to determine  the fields when receiving back them
			@#$ for tile
            @$# for metaDescription
            $#@ for description
            #$@ for tags*/

            languages.forEach(lang => {
                const titleDiv = document.createElement('div');
                titleDiv.id = `title_${lang}`;
                titleDiv.className  = 'notranslate';
                titleDiv.innerText = '@#$' + title;
                translationFields.appendChild(titleDiv);

                const metaDescriptionDiv = document.createElement('div');
                metaDescriptionDiv.id = `meta_description_${lang}`;
                metaDescriptionDiv.className  = 'notranslate';
                metaDescriptionDiv.innerText = '@$#' + metaDescription;
                translationFields.appendChild(metaDescriptionDiv);
				
				const descriptionDiv = document.createElement('div');
                descriptionDiv.id = `description_${lang}`;
                descriptionDiv.className  = 'notranslate';
                descriptionDiv.innerText = '$#@' + description;
                translationFields.appendChild(descriptionDiv);
				
				const tagsDiv = document.createElement('div');
                tagsDiv.id = `tags_${lang}`;
                tagsDiv.className  = 'notranslate';
                tagsDiv.innerText = '#$@' + tags;
                translationFields.appendChild(tagsDiv);
            });
        }
		
		
		async function translateAndSave() {
		    
			//get input values for blank/empty checking
			//title is a global var
		    title = document.getElementById('title').value; //title is a global var
            const metaDescription = document.getElementById('meta-description').value;
            const description = document.getElementById('description').value;
            const tags = document.getElementById('tags').value;

            if (!title || !metaDescription || !description || !tags) {
                alert("Please fill in both fields");
                return;
            }

            createTranslationFields(title, metaDescription, description, tags);

            //add main english inputs to translations list
			translations['en'] = {
				title: title,
				metaDescription: metaDescription,
				//replacing new line to br, for just english
				description: description.replace(/\n/g, '<br/><br/>'), 
				tags: tags
			};
			
			for (let i = 0; i < languages.length; i++) {
                const lang = languages[i];
				
				//language specific divs
                const titleDiv = document.getElementById(`title_${lang}`);
                const metaDescriptionDiv = document.getElementById(`meta_description_${lang}`);
                const descriptionDiv = document.getElementById(`description_${lang}`);
                const tagsDiv = document.getElementById(`tags_${lang}`);

                // Remove 'notranslate' class to allow translation
                titleDiv.classList.remove('notranslate');
                metaDescriptionDiv.classList.remove('notranslate');
                descriptionDiv.classList.remove('notranslate');
                tagsDiv.classList.remove('notranslate');
				
				/*ALL THE MAIN TRANSLATION DONE HERE*/
				
                // Trigger Google Translate for this language
                document.querySelector('.goog-te-combo').value = lang;
                document.querySelector('.goog-te-combo').dispatchEvent(new Event('change'));

                // Wait for translation to be applied
                await new Promise(resolve => setTimeout(resolve, 1500));
				
				
			}
		
		}
		
		
		function saveToFile(data, filename, allDone) {
            const formData = new FormData();
            formData.append('data', JSON.stringify(data));
            formData.append('filename', filename);

            fetch('xbay-poster/save-translations.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
			  if(allDone){
                alert(result);
			  }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
		
		
		
		/*AI section*/
		
		const titleField = document.getElementById('title');
		const metaDescriptionField = document.getElementById('meta-description');
		const descriptionField = document.getElementById('description');
		const charCounter = document.getElementById('char-count');

		titleField.addEventListener('input', () => {
		  metaDescriptionField.value = `Write a meta description in this topic: ${titleField.value}`;
		  descriptionField.value = `Make long paragraph in this topic: ${titleField.value}`;
		  updateCharCount();
		});
		
		metaDescriptionField.addEventListener('input', () => {
		  updateCharCount();
		});

		function updateCharCount() {
		  const charCount = metaDescriptionField.value.length;
		  charCounter.textContent = `${charCount}/140`; // 140 for meta description
		}
		
		function writeMetaDescriptionAI(){
			const formData = new FormData();
            formData.append('text', metaDescriptionField.value);
            //formData.append('filename', filename);

            fetch('xbay-poster/AI-fetch-meta-description.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
			    metaDescriptionField.value = result;
				updateCharCount();
			  
            })
            .catch(error => {
                console.error('Error:', error);
            });
		}
		
		function writeDescriptionAI(){
			const formData = new FormData();
            formData.append('text', descriptionField.value);
            //formData.append('filename', filename);

            fetch('xbay-poster/AI-fetch-description.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
			  descriptionField.value = result;
            })
            .catch(error => {
                console.error('Error:', error);
            });
		}


	</script>