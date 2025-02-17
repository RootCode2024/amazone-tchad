<template>
    <div>
        <div class="loader">
            <div class="iata_code departure_city">AMAZONE</div>
            <div class="plane">
                <img src="https://zupimages.net/up/19/34/4820.gif" class="plane-img">
            </div>
            <div class="earth-wrapper">
                <div class="earth"></div>
            </div>  
            <div class="iata_code arrival_city">TCHAD</div>
        </div>
    </div>
</template>

<script setup>
  import planeIcon from '../Assets/svg/plane.png';
</script>

<style scoped>
body {
  background-color: #fff;
  font-family: 'Roboto', sans-serif;
  margin: 0;
  padding: 0;
  overflow: hidden;
}

.loader {
  text-align: center;
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  width: 100%;
  height: 100%;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  overflow: hidden;
}

.wait {
  margin: 5rem 0;
  font-size: 2rem;
  color: #056c8f;
}

.iata_code {
  font-size: 4rem;
  opacity: 0.8;
  position: absolute;
  color: #056c8f;
  font-weight: bold;
}

.departure_city {
  left: 15%;
}

.arrival_city {
  right: 24%;
}

.plane {
  position: absolute;
  top: 53%;
  transform: translateY(-50%);
  width: 100%;
  max-width: 250px;
}

.plane-img {
  -webkit-animation: spin 2.5s linear infinite;
  -moz-animation: spin 2.5s linear infinite;
  animation: spin 2.5s linear infinite;
}

@-moz-keyframes spin {
  100% {
    -moz-transform: rotate(360deg);
  }
}

@-webkit-keyframes spin {
  100% {
    -webkit-transform: rotate(360deg);
  }
}

@keyframes spin {
  100% {
    -webkit-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}

.earth-wrapper {
  position: absolute;
  margin: 0 auto;
  width: 100%;
  padding-top: 2.7rem;
}

.earth {
  width: 160px;
  height: 160px;
  background: url("https://zupimages.net/up/19/34/6vlb.gif");
  border-radius: 100%;
  background-size: 340px;
  animation: earthAnim 12s infinite linear;
  margin: 0 auto;
  border: 1px solid #CDD1D3;
}

@keyframes earthAnim {
  0% { background-position-x: 0; }
  100% { background-position-x: -340px; }
}

@media screen and (max-width: 420px) {
  .departure_city {
    top: 20%;
    font-size: 4rem;
  }

  .arrival_city {
    top: 85%;
    font-size: 4rem;
  }

  .wait {
    font-size: 1.5rem;
  }
}
</style>
