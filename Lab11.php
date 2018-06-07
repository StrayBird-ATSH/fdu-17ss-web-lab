<html>
<head>
  <title>LRC 歌词编辑器</title>
  <meta charset="utf-8">
  <style>
    nav ul {
      position: fixed;
      z-index: 99;
      right: 5%;
      border: 1px solid darkgray;
      border-radius: 5px;
      list-style: none;
      padding: 0;
    }

    .tab {
      padding: 1em;
      display: block;
    }

    .tab:hover {
      cursor: pointer;
      background-color: lightgray !important;
    }

    td {
      padding: 0.2em;
    }

    textarea[name="edit_lyric"] {
      width: 100%;
      height: 50em;
    }

    input[type="button"] {
      width: 100%;
      height: 100%;
    }

    input[type="submit"] {
      width: 100%;
      height: 100%;
    }

    #td_submit {
      text-align: center;
    }

    select {
      display: block;
    }

    #lyricDisplay {
      overflow: hidden;
      width: 800px;
      height: 480px;
      box-shadow: 0 0 5px black;
      margin: auto auto;
    }

    #lyricDisplay #words {
      cursor: default;
      transition: all 0.5s ease;
      width: 100%;
      height: 100%;
      text-align: center;
      font-size: 20px;

    }

    #words li {
      height: 48px;
      line-height: 48px;
    }

    #words {
      color: red;
    }

    ul, li {
      margin: 0;
      padding: 0;
      list-style: none;
    }
  </style>
</head>
<body>
<nav>
  <ul>
    <li id="d_edit" class="tab">Edit Lyric</li>
    <li id="d_show" class="tab">Show Lyric</li>
  </ul>
</nav>

<!--歌词编辑部分-->
<section id="s_edit" class="content">
  <form id="f_upload" enctype="multipart/form-data" method="post" name="form">
    <p>请上传音乐文件</p>

    <audio controls preload="auto">
      <p>Browser doesn't support the audio control</p>
    </audio>

    <input type="file" name="file_upload" onchange="autoPlayMusic()">
    <table>
      <tr>
        <td>Title: <input type="text"></td>
        <td>Artist: <input type="text"></td>
      </tr>
      <tr>
        <td colspan="2"><textarea name="edit_lyric" id="edit_lyric"></textarea></td>
      </tr>
      <tr>
        <td><input type="button" value="插入时间标签"></td>
        <td><input type="button" value="替换时间标签"></td>
      </tr>
      <tr>
        <td colspan="2" id="td_submit"><input type="submit" value="Submit"></td>
      </tr>
    </table>
  </form>
</section>

<!--歌词展示部分-->
<section id="s_show" class="content">
  <form action="" name="formDisplay">
    <select name="songName" title="" onchange="changeSong()">
      <!--TODO: 在这里补充 html 元素，使点开 #d_show 之后这里实时加载服务器中已有的歌名-->
      <?php
      $dir = dirname(__FILE__) . "/files";
      //PHP遍历文件夹下所有文件
      $handle = opendir($dir . ".");
      //定义用于存储文件名的数组
      $array_file = array();
      while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != ".." &&
            strrchr(strtolower($file), ".mp3") == ".mp3") {
          $array_file[] = $file; //输出文件名
        }
      }
      closedir($handle);
      foreach ($array_file as $i => $music) {
        echo '<option value=' . $i . '>' . $music . '</option>';
      } ?>
    </select>
    <audio controls preload="auto">
      <p>Browser doesn't support the audio control</p>
    </audio>
    <!--TODO: 在这里补充 html 元素，使选择了歌曲之后这里展示歌曲进度条，并且支持上下首切换-->
  </form>
  <div id="lyricDisplay">
    <ul id="words" style="margin-top: 240px"></ul>
  </div>
</section>
</body>
<script src="js/jquery-3.3.1.js"></script>
<script>

  // 界面部分
  document.getElementById("d_edit").onclick = function () {
    click_tab("edit");
  };
  document.getElementById("d_show").onclick = function () {
    click_tab("show");
  };
  let audioEdit = document.getElementsByTagName("audio")[0];
  let audioDisplay = document.getElementsByTagName("audio")[1];
  let lyricDisplay = document.getElementById("lyricDisplay");
  let lyricArray = [];
  document.getElementById("d_show").click();
  let count = 0;

  function click_tab(tag) {
    for (let i = 0; i < document.getElementsByClassName("tab").length; i++)
      document.getElementsByClassName("tab")[i].style.backgroundColor = "transparent";
    for (let i = 0; i < document.getElementsByClassName("content").length; i++)
      document.getElementsByClassName("content")[i].style.display = "none";

    document.getElementById("s_" + tag).style.display = "block";
    document.getElementById("d_" + tag).style.backgroundColor = "darkgray";
  }

  // Edit 部分
  var edit_lyric_pos = 0;
  document.getElementById("edit_lyric").onmouseleave = function () {
    edit_lyric_pos = document.getElementById("edit_lyric").selectionStart;
  };

  // 获取所在行的初始位置。
  function get_target_pos(n_pos) {
    if (n_pos === undefined) n_pos = edit_lyric_pos;
    let value = document.getElementById("edit_lyric").value;
    let pos = 0;
    for (let i = n_pos; i >= 0; i--) {
      if (value.charAt(i) === '\n') {
        pos = i + 1;
        break;
      }
    }
    return pos;
  }

  // 选中所在行。
  function get_target_line(n_pos) {
    let value = document.getElementById("edit_lyric").value;
    let f_pos = get_target_pos(n_pos);
    let l_pos = 0;
    for (let i = f_pos; ; i++) {
      if (value.charAt(i) === '\n') {
        l_pos = i + 1;
        break;
      }
    }
    return [f_pos, l_pos];
  }

  function get_current_time() {
    let timeString = "[";
    let time = audioEdit.currentTime;
    timeString += time / 60;
  }

  function autoPlayMusic() {
    audioEdit.src = document.form.file_upload.file;
    audioEdit.play();
  }

  /* HINT:
   * 已经帮你写好了寻找每行开头的位置，可以使用 get_target_pos()
   * 来获取第一个位置，从而插入相应的歌词时间。
   * 在 textarea 中，可以通过这个 DOM 节点的 selectionStart 和
   * selectionEnd 获取相对应的位置。
   *
   * TODO: 请实现你的歌词时间标签插入效果。
   */

  /* TODO: 请实现你的上传功能，需包含一个音乐文件和你写好的歌词文本。
   */
  function changeSong() {
    let selectedIndex = document.formDisplay.songName.selectedIndex;
    audioDisplay.src = "./files/" + document.formDisplay.songName[selectedIndex].text;
    audioDisplay.play();
    let path = audioDisplay.src;
    ajaxFunction(path.slice(0, path.length - 4) + ".lrc");
  }

  function ajaxFunction(the_request_url) {
    let xmlHttp = new XMLHttpRequest();
    xmlHttp.open('GET', the_request_url, true);
    xmlHttp.onreadystatechange = function () {
      if (xmlHttp.readyState === 4 && xmlHttp.status === 200) {
        lyricDisplay.value = xmlHttp.responseText;
        prepareLyricsData();
      }
    };
    xmlHttp.send(null);
  }

  /* HINT:
   * 实现歌词和时间的匹配的时候推荐使用 Map class，ES6 自带。
   * 在 Map 中，key 的值必须是字符串，但是可以通过字符串直接比较。
   * 每一行行高可粗略估计为 40，根据电脑差异或许会有不同。
   * 当前歌词请以粗体显示。
   * 从第八行开始，当歌曲转至下一行的时候，需要调整滚动条，使得当前歌
   * 词保持在正中。
   *
   * TODO: 请实现你的歌词滚动效果。
   */
  function formatTime(time) {
    let m = time.split(':')[0];
    let s = time.split(':')[1];
    return Number(m) * 60 + Number(s);
  }

  function prepareLyricsData() {
    let lyrics = lyricDisplay.value;
    let lyricData = lyrics.match(/[^\r\n]+/g);
    for (let i = 0; i < lyricData.length; i++) {
      let tmpTime = /\d+:\d+.\d+/.exec(lyricData[i]);
      let tmpLyric = lyricData[i].split(/[\\[]\d+:\d+.\d+]/);
      if (tmpTime != null)
        lyricArray.push({time: formatTime(String(tmpTime)), lyric: tmpLyric[1]});
    }
    for (let i = 0; i < lyricArray.length; i++) {
      let lyricBorder = document.getElementById('words');
      let lyricEl = document.createElement('li');
      lyricEl.innerHTML = lyricArray[i].lyric;
      lyricBorder.appendChild(lyricEl);
    }
    count = 0;
  }

  var validateTime = function (time, index) {
    if (index < lyricArray.length - 1) {
      if (time >= lyricArray[index].time && time <= lyricArray[index + 1].time) {
        return true;
      } else {
        return false;
      }
    } else {
      if (time <= audioDisplay.duration) {
        return true;
      } else {
        return false;
      }
    }

  };

  var wordEl = document.getElementById('words');
  var marTop = parseInt(wordEl.style.marginTop);
  audioDisplay.ontimeupdate = function () {
    var time = audioDisplay.currentTime;
    if (!validateTime(time, count)) {
      count++;
    }
    wordEl.style.marginTop = (marTop - count * 48) + 'px';
    var li = wordEl.querySelectorAll('li');
    for (var i = 0; i < li.length; i++) {
      li[i].removeAttribute('class');
    }
    wordEl.querySelectorAll('li')[count].setAttribute('class', 'sel');
    if (audioDisplay.ended) {
      wordEl.style.marginTop = marTop + 'px';
      count = 0;
    }
  }
  audioDisplay.onseeked = function () {
    var cur_time = audioDisplay.currentTime;
    for (var _i = 0; _i <= lyricArray.length - 1; _i++) {
      if (cur_time >= lyricArray[_i].time && cur_time <= lyricArray[_i + 1].time)
        count = _i;
    }
  }
</script>
</html>
