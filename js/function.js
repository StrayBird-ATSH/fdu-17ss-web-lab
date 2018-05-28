function outputCountryBox(name, continent, cities, photos) {
    document.write('<div class="item" >');
    document.write('<h2>' + name + '</h2>');
    document.write('<p>' + continent + '</p>');
    outputCities(cities);
    outputPhotos(photos);
    document.write('<button>Visit</button>');
    document.write('</div>');

    function outputCities(cities) {
        document.write('<div class="inner-box">');
        document.write('<h3>Cities</h3>');
        document.write('<ul>');
        for (let i = 0; i < cities.length; i++) {
            document.write('<li>' + cities[i] + '</li>');
        }
        document.write('</ul>');
        document.write('</div>');
    }

    function outputPhotos(photos) {
        document.write('<div class="inner-box">');
        document.write('<h3>Popular Photos</h3>');
        for (let i = 0; i < photos.length; i++) {
            document.write('<img src="images/' + photos[i] + '" class="photo">');
        }
        document.write('</div>');
    }
}

function inject() {
    for (let i = 0; i < countries.length; i++) {
        let c = countries[i];
        outputCountryBox(c.name, c.continent, c.cities, c.photos);
    }
    let parentNode = document.getElementsByClassName('flex-container justify')[0];
    let nodes = document.getElementsByClassName('item');
    for (let i = 0; i < nodes.length; i++) {
        parentNode.appendChild(nodes[i]);
    }
}

inject();