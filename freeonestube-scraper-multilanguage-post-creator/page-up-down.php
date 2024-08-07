<button id="pageUpButton" class="notranslate">↑</button>
<button id="pageDownButton" class="notranslate">↓</button>


<style>
#pageUpButton, #pageDownButton {
  position: fixed;
  bottom: 20px;
  right: 20px;
  background-color: #ccc;
  border: none;
  border-radius: 50%;
  width: 40px;
  height: 40px;
  cursor: pointer;
  z-index: 999;
}

#pageUpButton {
  bottom: 60px;
}

</style>

<script>
const pageUpButton = document.getElementById('pageUpButton');
const pageDownButton = document.getElementById('pageDownButton');

pageUpButton.addEventListener('click', () => {
  window.scrollTo({
    top: 0,
    behavior: 'smooth'
  });
});

pageDownButton.addEventListener('click', () => {
  window.scrollTo({
    top: document.body.scrollHeight,
    behavior: 'smooth'
  });
});


</script>
