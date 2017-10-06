import Vue from 'vue';
import axios from 'axios';

document.addEventListener('DOMContentLoaded', () => {
    const app = new Vue({
        el: '#app',
        data: {
            list: [],
            toggleAllCheckbox: true,
            markers: []
        },
        methods: {
            loadList() {
                axios.post('/get-persons').then(res => {
                    res.data.forEach( function(element) {
                        element.checked = true;
                    });
                    this.list = res.data;
                }).catch(err => {
                    alert('Неизвестная ошибка, подробности в консоли');
                    console.log(err);
                });
            },
            loadCoordinates() {
                axios.post('/get-coordinates', {
                    names: this.list.filter(value => value.checked).map(value => value.name)
                }).then(res => {
                    // скрываем все
                    this.markers.forEach((element) => {
                        element.marker.setVisible(false);
                    });

                    // есди загрузка в 1 раз то заполняем маркерами наш store
                    if(this.markers.length === 0) {
                        res.data.forEach(element => {
                            let distance = google.maps.geometry.spherical.computeDistanceBetween(
                                new google.maps.LatLng(element.latitude, element.longitude),
                                new google.maps.LatLng(55.752008, 37.617501)
                            );

                            let marker = new google.maps.Marker({
                                position: new google.maps.LatLng(element.latitude, element.longitude),
                                map: window.map,
                                title: element.fio,
                                icon: {
                                    path: "M0-48c-9.8 0-17.7 7.8-17.7 17.4 0 15.5 17.7 30.6 17.7 30.6s17.7-15.4 17.7-30.6c0-9.6-7.9-17.4-17.7-17.4z",
                                    fillColor: distance > 10000 ? '#FF0000' : '#00FF00',
                                    fillOpacity: .6,
                                    anchor: new google.maps.Point(0,0),
                                    strokeWeight: 0,
                                    scale: 1
                                }
                            });

                            let ballon = new google.maps.InfoWindow({
                                content: `<div>ФИО: <b>${element.fio}</b></div><div>Расстояние до кремля: <b>${~~distance}м.</b></div>` // ~~ - магия битовых операций
                            });

                            // элегантнее способа, к сожалению, нету
                            google.maps.event.addListener(marker, 'click', () => {
                                this.markers.forEach(marker => {
                                    marker.ballon.close();
                                });
                                ballon.open(window.map, marker);
                            });

                            this.markers[element.id] = {
                                marker,
                                ballon
                            };
                        });
                    } else { // или показываем нужные
                        res.data.forEach(element => {
                            this.markers[element.id].marker.setVisible(true);
                        });
                    }
                }).catch(err => {
                    alert('Неизвестная ошибка, подробности в консоли');
                    console.log(err);
                });
            }
        },
        mounted () {
            this.loadList();
        },
        watch: {
            toggleAllCheckbox: function (value) {
                this.list.forEach(element => {
                    element.checked = value;
                });
            },
            list: {
                handler: function () {
                    this.loadCoordinates();
                },
                deep: true
            }
        }
    });

    window.initMap = () => {
        // плохой поступок, но я все же так сделаю
        window.map = new google.maps.Map(document.querySelector('.app .app__map'), {
            center: new google.maps.LatLng(55.752008, 37.617501),
            zoom: 10
        });
    }
});