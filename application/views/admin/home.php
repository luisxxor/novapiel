<style>
  html {
    overflow-y: hidden;
  }
</style>

<div id="particles-js"></div>
<script src="http://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
<script>
  particlesJS.load('particles-js', <?PHP echo json_encode(base_url('assets/particles/particles.json')); ?>);
</script>