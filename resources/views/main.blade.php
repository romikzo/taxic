<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Taxic - Test</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,500" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="/css/app.css">
        <script src="/js/app.js"></script>
    </head>
    <body>
        <div id="app" class="app">
            <div class="app__map"></div>
            <div class="app__list app-list">
                <div class="app-list__head">Taxic clients</div>
                <div class="app-list__body">
                    <div class="app-list-person app-list-person_head">
                        <div class="app-list-person__checkbox"><input type="checkbox" v-model="toggleAllCheckbox" ></div>
                        <div class="app-list-person__name" @click="toggleAllCheckbox = toggleAllCheckbox ? false : true">Имя</div>
                        <div class="app-list-person__count" @click="toggleAllCheckbox = toggleAllCheckbox ? false : true">Кол-во</div>
                    </div>
                    <div class="app-list-person" v-for="person in list">
                        <div class="app-list-person__checkbox"><input type="checkbox" v-model="person.checked" ></div>
                        <div class="app-list-person__name" @click="person.checked = person.checked ? false : true">@{{ person.name }}</div>
                        <div class="app-list-person__count" @click="person.checked = person.checked ? false : true">@{{ person.count }}</div>
                    </div>
                </div>
            </div>
        </div>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAp44cdeqj6KkNEvq89xZefSmH_BfCmI_g&libraries=geometry&callback=initMap" async defer></script>
    </body>
</html>
