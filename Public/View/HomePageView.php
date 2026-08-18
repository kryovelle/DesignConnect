<?php
require_once __DIR__ . '/GlobalView.php';
Class HomePageView extends GlobalView{
  

function content($contact,$name,$requests,$avatar){
  ?>
<body>
<!-- ================= NAV ================= -->
  <?php $this->nav($name,$avatar) ?>

  <!-- ================= HERO ================= -->
  <header class="hero" id="home">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="hero-grid">
      <div>
        <div class="hero-eyebrow eyebrow"><span class="dot"></span> A marketplace built only for design work</div>
        <h1>Great design,<br>on <em>demand</em>.</h1>
        <p class="lead">Post what you need — a logo, a website, a feed worth scrolling. Real designers send real proposals. You pick who gets it, then talk directly.</p>
        <div class="hero-ctas">
          <a href="#post" class="btn btn-solid">Post a Request</a>
          <a href="#browse" class="btn btn-outline">Browse Designers</a>
        </div>
        <div class="hero-stats">
          <div><span class="num">1,240+</span><span class="label">Requests posted</span></div>
          <div><span class="num">380+</span><span class="label">Active designers</span></div>
          <div><span class="num">6</span><span class="label">Design categories</span></div>
        </div>
      </div>

      <div class="hero-visual">
        <div class="float-card fc-1">
          <div class="thumb"></div>
          <span class="cat">Logo &amp; Branding</span>
          <div class="title">Coffee shop identity</div>
          <div class="meta"><span>3 proposals</span><b>$150</b></div>
        </div>
        <div class="float-card fc-2">
          <div class="thumb"></div>
          <span class="cat">Print</span>
          <div class="title">Event flyer set</div>
          <div class="meta"><span>Open</span><b>$90</b></div>
        </div>
        <div class="float-card fc-3">
          <div class="thumb"></div>
          <span class="cat">Web / UI</span>
          <div class="title">Portfolio homepage</div>
          <div class="meta"><span>5 proposals</span><b>$300</b></div>
        </div>
      </div>
    </div>

    <div class="marquee">
      <div class="marquee-track" id="marqueeTrack">
        <span>Logo &amp; Branding</span><span>Web / UI Design</span><span>Social Media</span><span>Packaging</span><span>Illustration</span><span>Print</span>
        <span>Logo &amp; Branding</span><span>Web / UI Design</span><span>Social Media</span><span>Packaging</span><span>Illustration</span><span>Print</span>
      </div>
    </div>
  </header>

  <!-- ================= CATEGORIES ================= -->
  <section class="categories" id="categories">
    <div class="section-head reveal">
      <div class="eyebrow">Explore by category</div>
      <h2>Whatever kind of design, there's someone here for it</h2>
    </div>
    <div class="cat-row">
      <a class="cat-chip c1 reveal"><span class="sw"></span>Logo &amp; Branding</a>
      <a class="cat-chip c2 reveal"><span class="sw"></span>Web / UI Design</a>
      <a class="cat-chip c3 reveal"><span class="sw"></span>Social Media</a>
      <a class="cat-chip c4 reveal"><span class="sw"></span>Packaging</a>
      <a class="cat-chip c5 reveal"><span class="sw"></span>Illustration</a>
      <a class="cat-chip c6 reveal"><span class="sw"></span>Print</a>
    </div>
  </section>

  <!-- ================= HOW IT WORKS ================= -->
  <section class="how" id="how">
    <div class="section-head reveal">
      <div class="eyebrow">Three steps, no middleman</div>
      <h2>How DesignConnect works</h2>
      <p>No bidding wars, no platform fees skimmed off your budget — just requests, proposals, and a direct line to the person doing the work.</p>
    </div>
    <div class="how-steps">
      <div class="step reveal">
        <div class="num">1</div>
        <h3>Post a request</h3>
        <p>Describe what you need, set a budget and deadline, attach a reference image if you've got one.</p>
      </div>
      <div class="step reveal">
        <div class="num">2</div>
        <h3>Get proposals</h3>
        <p>Designers who work in that category send a pitch, samples, and how to reach them — visible only to you.</p>
      </div>
      <div class="step reveal">
        <div class="num">3</div>
        <h3>Pick a designer</h3>
        <p>Compare portfolios, reach out directly, and mark the request as in progress whenever you're ready.</p>
      </div>
    </div>
  </section>

  <!-- ================= OPEN REQUESTS ================= -->
  <section class="requests" id="browse">
    <div class="req-head-row reveal">
      <h2>Open requests, right now</h2>
      <a href="/DesignConnect/Public/Browse/">See all requests →</a>
    </div>
    <div class="req-grid">
      <?php foreach($requests as $request):
              $randomClass = 'r' . rand(1, 6);
              $imageUrl = '/DesignConnect/images/' . htmlspecialchars($request['reference_image']);?>

        <div class="req-card reveal">
            <div class="req-thumb <?php echo $randomClass; ?>">
                  <img
                      src="<?php echo $imageUrl; ?>"
                      alt="Reference Image"
                      onerror="this.style.display='none'"
                      class="img-browse"
                  >
            </div>
            <div class="req-body">
              <span class="req-tag"><?php echo htmlspecialchars($request['category'])?></span>
              <h3><?php echo htmlspecialchars($request['title'])?></h3>
              <div class="req-foot"><span class="budget"><?php echo htmlspecialchars($request['category'])?></span><a class="view" href="/DesignConnect/Public/RequestDetail/?id=<?php echo htmlspecialchars($request['id'])?>">View</a></div>
            </div>
         </div>
       <?php endforeach;?>
    </div>
  </section>


              

  <!-- ================= CTA BAND ================= -->
  <section class="cta-band">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <h2>Got a project sitting in your head? Put it in front of designers who actually want it.</h2>
    <div class="btn-row">
      <a href="/DesignConnect/Public/post/" class="btn btn-solid">Post a Request</a>
      <a href="/DesignConnect/Public/Join/" class="btn btn-outline">Join as a Designer</a>
    </div>
  </section>

  <!-- ================= FOOTER ================= -->
    <?php $this->footer($contact)?>
<?php
}

function displayHomePageView($contact,$name,$requests,$avatar){
  $this->head();
  $this->content($contact,$name,$requests,$avatar);
  $this->closePage();
}

}

?>