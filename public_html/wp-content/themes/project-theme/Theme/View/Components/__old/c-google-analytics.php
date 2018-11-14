<script async src="https://googletagmanager.com/gtag/js?id=<?= $data['UA'] ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '<?= $data['UA'] ?>');
</script>
<!-- End of Google Analytics -->
