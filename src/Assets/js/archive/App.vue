<template>
  <div class="u-l-container--center" data-in-viewport>
      <div class="u-l-container u-l-container--row u-l-horizontal-padding u-l-vertical-padding u-l-vertical-padding--small">
          <!-- START: Meta Column -->
          <div class="c-meta">
              <div class="c-meta__data">
                  <form action="">
                      <div class="c-meta__section">
                          <h3 class="c-meta__title">Sort By</h3>
                          <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="sortby" value="artist-a-z"><span>Artist A-Z</span></label></p>
                          <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="sortby" value="price-low-to-high"><span>Price: low to high</span></label></p>
                          <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="sortby" value="price-high-to-low"><span>Price: high to low</span></label></p>
                      </div>

                      <div class="c-meta__section">
                          <h3 class="c-meta__title">By Medium</h3>
                          <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bymedium" value="Painting"><span>Painting</span></label></p>
                          <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bymedium" value="Sculpture"><span>Sculpture</span></label></p>
                          <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bymedium" value="Work on Paper"><span>Work on Paper</span></label></p>
                          <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bymedium" value="Mixed Media"><span>Mixed Media</span></label></p>
                          <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bymedium" value="Collage"><span>Collage</span></label></p>
                          <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bymedium" value="Installation"><span>Installation</span></label></p>
                      </div>

                      <div class="c-meta__section">
                          <h3 class="c-meta__title">By Price</h3>
                          <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="byprice" value="Under $50k"><span>Under $50k</span></label></p>
                          <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="byprice" value="$50 – 100k"><span>$50 – 100k</span></label></p>
                          <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="byprice" value="$100 – 150k"><span>$100 – 150k</span></label></p>
                          <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="byprice" value="$150 – 200k"><span>$150 – 200k</span></label></p>
                          <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="byprice" value="$200 – 250k"><span>$200 – 250k</span></label></p>
                          <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="byprice" value="$250 – 500k"><span>$250 – 500k</span></label></p>
                          <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="byprice" value="Over $500k"><span>Over $500k</span></label></p>
                      </div>

                      <div class="c-meta__section">
                      <h3 class="c-meta__title">By Decade</h3>
                      <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bydecade" value="Pre-1900"><span>Pre-1900</span></label></p>
                      <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bydecade" value="1900 – 1945"><span>1900 – 1945</span></label></p>
                      <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bydecade" value="1945 – 1960"><span>1945 – 1960</span></label></p>
                      <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bydecade" value="1960 – 1970"><span>1960 – 1970</span></label></p>
                      <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bydecade" value="1970 – 1980"><span>1970 – 1980</span></label></p>
                      <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bydecade" value="1980 – 1990"><span>1980 – 1990</span></label></p>
                      <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bydecade" value="1990 – 2000"><span>1990 – 2000</span></label></p>
                      <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bydecade" value="2000 – 2010"><span>2000 – 2010</span></label></p>
                      <p><label class="c-meta__label"><input class="c-meta__radio" type="radio" name="bydecade" value="2010 – Present"><span>2010 – Present</span></label></p>
                      </div>
                  </form>
              </div>
          </div>
          <!-- END: Meta Column -->
        <VWorkList :works="works" />
      </div>
  </div>
</template>
<script>
import VWorkList from './components/VWorkList'

export default {
  props: {
    works: Array
  }
}
</script>
