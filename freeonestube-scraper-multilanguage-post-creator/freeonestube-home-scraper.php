<!DOCTYPE html>
<html>
<head>
    <title>Freeonestube list scraper</title>    
</head>
<?php

if(isset($_GET['url'])){ 
   $url = $_GET['url']; 
   
   $html =  file_get_contents($url);
   $clickableList = createClickableList($html);
   echo $clickableList;
}
else{ $url = "https://freeonestube.com/"; }
?>
<body>
    <div class="input-row">
	 <form>
        <input type="text" placeholder="Enter Freeonestube list URL" value="<?php echo $url; ?>" name="url">
        <input type="submit" value="Submit">
	 </form>
    </div>
	
    <div id="output"></div>


<?php

function createClickableList($html) {
  $dom = new DOMDocument();
  @$dom->loadHTML($html); // Suppress potential errors from invalid HTML

  $xpath = new DOMXPath($dom);
  $links = $xpath->query("//a[@class='infos pt-2']");

  $listItems = [];
  foreach ($links as $link) {
    $href = $link->getAttribute('href');
    $title = $link->getAttribute('title');
    $listItems[] = "<li><a href='freeonestube-video-pages-scrape-to-AI-post-generate.php?url=$href' title='$title' target='_blank'>$title</a></li>";
  }

  $list = "<ol id='linkList'>" . implode("\n", $listItems) . "</ol>";
  return $list;
}

// Example usage:

?>








<style>
        body {
            font-family: Arial, sans-serif;
            text-align: left;
            background-color: #f0f0f0;
        }

        .input-row {
            display: flex;
            justify-content: left;
            align-items: left;
            margin: 20px;
        }

        input[type="text"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 500px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
	
	<!--script>
	/*this opens links in new tab in 20 seconds delay*/
	function openLinksSequentially(list) {
  const links = list.querySelectorAll('a');
  let index = 0;

  function openNextLink() {
    if (index < links.length) {
      const link = links[index];
      setTimeout(() => {
        window.open(link.href, '_blank');
        index++;
        openNextLink();
      }, 20000); // 20 seconds delay
    }
  }

  openNextLink();
}

const linkList = document.getElementById('linkList'); // Replace with your list ID
openLinksSequentially(linkList);

	</script-->
	
	<!--script>
	/*this fetches links in 20 seconds delay*/
	function fetchAndAppendContentWithDelay(list) {
  const links = list.querySelectorAll('a');
  const outputDiv = document.getElementById('output');
  let index = 0;

  function fetchNextContent() {
    if (index < links.length) {
      setTimeout(() => {
        const link = links[index];
        fetch(link.href)
          .then(response => response.text())
          .then(data => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(data, 'text/html');
            const targetDiv = doc.getElementById('output');

            if (targetDiv) {
              const clone = targetDiv.cloneNode(true);
              outputDiv.appendChild(clone);
            } else {
              console.error('Div with ID "output" not found on fetched page.');
            }

            index++;
            fetchNextContent();
          })
          .catch(error => {
            console.error('Error fetching content:', error);
            // Handle errors here, e.g., display an error message
          });
      }, 13000); // 13 seconds delay
    }
  }

  fetchNextContent();
}

const linkList = document.getElementById('linkList');
if(linkList){
 fetchAndAppendContentWithDelay(linkList);
}

	</script->

</body>
</html>