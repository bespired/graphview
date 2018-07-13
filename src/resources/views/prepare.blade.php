<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>GraphView</title>

        <!-- DATA and CSRF Stuff -->

        <!-- Styles -->
        <link href="{{ asset('vendor/bespired/graphview/css/app.css') }}" rel="stylesheet" type="text/css">
   </head>
   <body>


    @foreach($files as $key => $file)
        <canvas id="canvas-{{ $key }}" width="480" height="240"></canvas><br>
    @endforeach

    <script>

        @foreach($files as $key => $file)
            var newImg{{$key}} = new Image();

            newImg{{$key}}.onload = drawImageActualSize;
            newImg{{$key}}.id  = {{ $key }};
            newImg{{$key}}.src = '{{ $file }}';

        @endforeach

        function drawImageActualSize() {
            var canvas = document.getElementById('canvas-' + this.id);
            var ctx = canvas.getContext('2d');
            let w = this.width;
            let h = this.height;

            ctx.drawImage(this, 0, 0, w, h);
            ctx.fillStyle="#FF0000";

            let cw= Math.floor(w / 2);
            let ch= Math.floor(h / 2);
            let w2= Math.floor(w / 2);
            let h2= Math.floor(h / 2);

            var d = ctx.getImageData(0, 0, w, h);
            var c = ctx.getImageData(cw, ch, 1, 1).data;

            let dx, dy, dist = {};

            let steps= [
                  0,
                  5,  10,  15,  20,  26,  30,  32,  35,  40,  45,
                 50,  55,  60,  65,  70,  75,  80,  85,  90,
                 95, 100, 105, 110, 114, 120, 125, 130, 135,
                140, 145, 148, 154, 160, 165, 170, 175, 180,
                185, 190, 195, 200, 206, 210, 212, 215, 220, 225,
                230, 235, 240, 245, 250, 255, 260, 265, 270,
                275, 280, 285, 290, 295, 300, 305, 310, 315,
                320, 325, 328, 330, 334, 340, 345, 350, 355, 360
            ];

            for(let r=0 ; r < steps.length ; r++){

                let deg = steps[r];
                let search = true;
                let found  = false;
                a = deg % 360;

                for(let s=1 ; s < w * 1 ; s++){

                    d= s / 1;

                    let px = Math.floor(d * Math.cos(a * 0.01745329252) + cw);
                    let py = Math.floor(d * Math.sin(a * 0.01745329252) + ch);

                    dx = px - cw;
                    dy = py - ch;
                    ds = Math.round(Math.sqrt( dx * dx + dy * dy ));

                    if (( px > 0 ) && ( px < w )){
                        if (( py > 0 ) && ( py < h )){

                            c = ctx.getImageData(px, py, 1, 1).data;

                            if ((search) && (c[1] < 128)){
                                dist[deg] = ds;
                                search = false;
                            }

                            if (!search){
                                ctx.fillRect(px, py, 1, 1);
                            }
                        }
                    }
                }
            }

            for(let r=0 ; r < steps.length ; r++){
                let a = steps[r];
                let s = dist[a];
                let px = s * Math.cos(a * 0.01745329252) + cw;
                let py = s * Math.sin(a * 0.01745329252) + ch;
                ctx.fillRect(px-1, py-1, 3, 3);
            }






            let n = {
                0:'multiple-private:',
                1:'multiple-public:',
                2:'single-private:',
                3:'single-public:',
            }

            console.log(n[this.id], dist);
        }


    </script>


    </body>
</html>