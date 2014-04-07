<?php
    include_once("./_config/config.php");

   


?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>title</title>

<?php include_once("./incl/setting.php");?>

<script type="text/javascript">
// $(function(){

// var files = {};

// $.get('./../output2.csv', function(ret) {
//     var lines = ret.split("\n");
//     $.each(lines.slice(1), function(i, line) {
//         if (line === '') {
//             return;
//         }
//         var id = line.split(',')[0];
//         files[id] = {
//             file: line.split(',')[1],
//             page: line.split(',')[2],
//             url: line.split(',')[3],
//             width: line.split(',')[4],
//             height: line.split(',')[5]
//         };
//     });
//     $('#list').empty();

//     $.each(files, function(id, data) {
//         $('#list').append($('<li></li>').attr('data-id', id).text(data.file + '(' + data.page + ')'));
//     });
// }, 'text');


// $('#input').submit(function(e) {
//     e.preventDefault();
//     show_data($('input[name=text]').val());
// });

// var img_info;
// var img_data;
// $('#list').delegate('li', 'click', function() {
//     var id = $(this).attr('data-id');
//     show_data(id);
// });

// var ocr_text;

// var show_data = function(id) {
//     var data = files[id];
//     var window_width = $(window).width();
//     var window_height = $(window).height();

//     ocr_text = {};

//     $.get('./../cells-text/' + id + '.json', {}, function(ret) {
//         ocr_text = ret;
//     }, 'json');
    
//     img_data = new Image;
//     img_data.src = data.url;
//     img_data.onload = function() {
//         $('#img_canvas').css({
//             'background': 'url(' + data.url + ')',
//             'background-size': '400px 300px',
//             'width': '400px',
//             'height': '300px'
//         });
//         $.get('./../outputs2/' + id + '.json', function(ret) {
//             img_info = ret;
//             $('#board').show();
//             $('#table').empty();

//             for (var i = 0; i < ret.cross_points[0].length - 1; i++) {
//                 var tr_dom = $('<tr></tr>');
//                 for (var j = 0; j < ret.cross_points.length - 1; j++) {
//                     var td_dom = $('<td></td>');
//                     var cell_id = id + '-' + (i + 1) + '-' + (j + 1);
//                     var cell_text = ocr_text[cell_id];
//                     td_dom.data({
//                         'x': j,
//                         'y': i,
//                         'cell-id': cell_id,
//                         'text': ocr_text[cell_id]
//                     }).text();
//                     if(typeof cell_text === 'string') {
//                         td_dom.addClass('hasText');
//                         td_dom.css('background-color', 'green');
//                     }
//                     tr_dom.append(td_dom);
//                 }
//                 $('#table').append(tr_dom);
//             }
//         }, 'json');
//     };
// };

// $('#table').delegate('td', 'mouseover', function() {
//     var x = $(this).data('x');
//     var y = $(this).data('y');
//     if (undefined === ocr_text[$(this).data('cell-id')]) {
//         $('#canvas_text').text('cell-id: ' + $(this).data('cell-id'));
//     } else {
//         $('#canvas_text').html('cell-id: ' + $(this).data('cell-id') + '<br />翻譯：' + $(this).data('text'));
//     }


//     $('#table td').css('background-color', '');
//     $('td.hasText').css('background-color', 'green');
//     $(this).css('background-color', 'red');

//     var lefttop = img_info.cross_points[x][y];
//     var rightdown = img_info.cross_points[x + 1][y + 1];
//     var source_width = parseInt(rightdown[0] - lefttop[0]);
//     var source_height = parseInt(rightdown[1] - lefttop[1]);
//     var target_width = (source_width > source_height) ? 400 : Math.floor(400 * source_width / source_height);
//     var target_height = (source_height > source_width) ? 400 : Math.floor(400 * source_height / source_width);

//     var img_context = $('#img_canvas')[0].getContext('2d');
//     img_context.clearRect(0, 0, 400, 300);
//     img_context.beginPath();
//     img_context.strokeStyle = 'red';
//     img_context.rect(
//             Math.floor(lefttop[0] * 400 / img_info.width),
//             Math.floor(lefttop[1] * 300 / img_info.height),
//             Math.floor((rightdown[0] - lefttop[0]) * 400 / img_info.width),
//             Math.floor((rightdown[1] - lefttop[1]) * 300 / img_info.height)
//             );
//     img_context.stroke();
//     $('#canvas')[0].getContext('2d').clearRect(0, 0, 400, 400);
//     $('#canvas')[0].getContext('2d').drawImage(
//             img_data,
//             lefttop[0], // source_x
//             lefttop[1], // source_y
//             source_width, // source_width
//             source_height, // source_height
//             0, // target_x
//             0, // target_y
//             target_width,
//             target_height
//             );
//     console.log(target_width + ' ' + target_height);
// });

// $('body').click(function() {
//     $('#board').hide();
// });


// });
</script>
</head>

<body>


<div>
    <?php 
        $files = array();
        $res = myquery(" select * from ".__table_prefix."filestatus order by filSno ");
        while($row = mysql_fetch_assoc($res)){
            $files[$row['filName']][$row['filPage']] = $row;
        }

        foreach ($files as $filName => $pages) {
            ksort($pages);
    ?>
    <div class="filelist">
        <h1><?=$filName;?></h1>
        <div class="filepages clearfix">
            <ul>
                <?php foreach ($pages as $no => $page) { ?>
                <li>
                    <a href="detail.php?id=<?=$page['filSno'];?>" class="btn">
                        <span class="icon-feather"></span>
                        <span class="mls"> <?=$page['filPage'];?></span>
                    </a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
    <?php } ?>
    
</div>



</body>
</html>