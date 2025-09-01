<?php
//欧阳鹏作品 2021.08.09
//随机输出一张图片或视频 来自壁纸多多所有数据

//随机大类
$ym = file_get_contents("https://www.bizhiduoduo.com/wallpaper/wplist.php?type=getcatev2");
$json = json_decode($ym, true);
$data = $json["data"];
$num = rand(0, (count($data) - 1));
$classid = $data[$num]["id"];
$classname = $data[$num]["name"];

//随机小类
$ym2 = file_get_contents("https://www.bizhiduoduo.com/wallpaper/wplist.php?type=get_class_label&classid=" . $classid);
$json2 = json_decode($ym2, true);
$data2 = $json2["data"];
$num2 = rand(0, (count($data2) - 1));
$labelid = $data2[$num2]["id"];
$labelname = $data2[$num2]["name"];
$pic_count = $data2[$num2]["pic_count"];
$video_count = $data2[$num2]["video_count"];

//随机图片
//设置请求资源类型
if ($_GET["res"] == "pic" || $_GET["res"] == null) {
  $res = "pic";
  $num3 = $pic_count;
} elseif ($_GET["res"] == "video") {
  $res = "video";
  $num3 = $video_count;
}
//计算随机页码
if ($num3 < 30) {
  $page = 0;
} else {
  $page = rand(0, (($num3 - $num3 % 30) / 30 - 1));
}
$ym3 = file_get_contents("https://www.bizhiduoduo.com/wallpaper/wplist.php?type=get_class_list&classid=" . $classid . "&labelid=" . $labelid . "&pg=" . $page . "&res=" . $res);
$json3 = json_decode($ym3, true);
$data3 = $json3["data"][$res]["res"];
$list = Array();
if ($_GET["count"] <= 30 && $_GET["count"] > $num3) {
  $pn = $num3;
} elseif ($_GET["count"] <= 30 && $_GET["count"] > 0) {
  $pn = $_GET["count"];
} else {
  $pn = 1;
}
for ($x = 0; $x < $pn; $x++) {
  $num4 = rand(0, (count($data3) - 1));
  $list[$x]["url"] = $data3[$num4]["url"];
  $list[$x]["thumb"] = $data3[$num4]["thumb"];
}
$newdata = Array(
  "name1" => $classname,
  "name2" => $labelname,
  "list" => $list
);

/*
模式列表 type
1. url重定向
2. thumb重定向
3. json
*/

$num4 = rand(0, (count($data3) - 1));
$burl = $data3[$num4]["url"];
$bthumb = $data3[$num4]["thumb"];
if ($_GET["type"] == "url") {
  header("Location:$burl");
} elseif ($_GET["type"] == "thumb") {
  header("Location:$bthumb");
} else {
  echo json_encode($newdata, JSON_UNESCAPED_UNICODE);
}
?>