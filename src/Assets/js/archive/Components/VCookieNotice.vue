<template>
   <cookie-law theme="hauser-theme"> 
      <div slot-scope="props" class="c-cookiepop">
         <p>
            This site uses cookies to improve user experience. By clicking 'Accept' or by continuing to use this site, you consent to our use of cookies. Click ‘Learn more’ for information on how we use cookies and how you can control them.
         </p>
         <div class="hw-button-wrapper">
            <button class="skew" @click="props.close"><span><a href="https://www.vip-hauserwirth.com/site-terms-of-use/">Learn more</a></span></button>
         </div>
         <div class="hw-button-wrapper">
            <button class="skew" @click="props.accept"><span>I accept</span></button>
         </div>
      </div>
   </cookie-law>
</template>
