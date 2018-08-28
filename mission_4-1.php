<?php
$msgsend='';
$msgname='';
$msgcome='';
$msgpass='';
$msgedit='';
$msgdel='';

$enu=NULL;
$ena='';
$eco='';
date_default_timezone_set('Japan');

try{
    $dsn='mysql:dbname=データベース名;host=localhost;charset=utf8mb4';
    $user='ユーザー名';
    $pass='パスワード';
    $pdo=new PDO($dsn,$user,$pass);

    if(isset($_POST['sb'])){
        $name=$_POST['name'];
        $comment=$_POST['comment'];
        $date=date("Y/m/d H:i:s");
        $password=$_POST['sendpass'];
        if($name!==''&&$comment!==''&&$password!==''){
            $str=$pdo->prepare("INSERT INTO yjdata (name,comment,date,password) VALUES(:name,:comment,:date,:password)");
                $str->bindParam(':name',$name,PDO::PARAM_STR);
                $str->bindParam(':comment',$comment,PDO::PARAM_STR);
                $str->bindParam(':date',$date,PDO::PARAM_STR);
                $str->bindParam(':password',$password,PDO::PARAM_STR);
                $str->execute();

                $msgsend='ご入力ありがとうございます<br>';
        }
        else{
            if($name===''){
                $msgname='名前を入力してください<br>';
            }
            if($comment===''){
                $msgcome='コメントを入力してください<br>';
            }
            if($password===''){
                $msgpass='パスワードを設定してください<br>';
            }
        }
    }
    elseif(isset($_POST['db'])){
        $num=$_POST['dn'];
        $dpass=$_POST['dpass'];
        if($num!==''&&$dpass!==''){
            $str="SELECT password FROM yjdata WHERE id=$num";
            $results=$pdo->query($str);
            foreach($results as $row){
            }
            if($dpass===$row["password"]){
                $dsql="DELETE FROM yjdata WHERE id=$num";
                $result=$pdo->query($dsql);
                $msgdel='投稿を削除しました<br>';
            }
            else{
                $msgpass='パスワードが正しくありません<br>';
            }
        }

        else{
            if($num===''){
                $msgdel='削除したい投稿の番号を入力してください<br>';
            }
            if($dpass===''){
                $msgpass='パスワードを入力してください<br>';
            }
        }
    }
    elseif(isset($_POST['eb'])){
        if(!empty($_POST['re'])){
            $num=$_POST['re'];
            $name=$_POST['name'];
            $comment=$_POST['comment'];
            $date=date("Y/m/d H:i:s");
            $password=$_POST['epass'];
                if($name!==''&&$comment!==''&&$password!==''){
                    $str=$pdo->prepare("UPDATE yjdata SET name=:name, comment=:comment, date=:date WHERE id=$num");
                    $str->bindParam(':name', $name, PDO::PARAM_STR);
                    $str->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $str->bindParam(':date', $date, PDO::PARAM_STR);
                    $str->execute();

                    $msgedit='投稿を編集しました<br>';
                }
                else{
                    if($name===''){
                        $msgname='名前を入力してください<br>';
                    }
                    if($comment===''){
                        $msgcome='コメントを入力してください<br>';
                    }
                    if($password===''){
                        $msgpass='パスワードを入力してください<br>';
                    }
                }
        }
        else{
            $num=$_POST['en'];
            $password=$_POST['epass'];
                if($num!==''&&$password!==''){
                    $str="SELECT name, comment, password FROM yjdata WHERE id=$num";
                    $result=$pdo->query($str);
                    foreach($result as $row){
                    }
                    if($password===$row["password"]){
                        $enu=$num;
                        $ena=$row["name"];
                        $eco=$row["comment"];
                        $msgedit='編集モードです。入力を書き換えたら編集ボタンを押してください<br>';
                    }
                    else{
                        $msgpass='パスワードが違います<br>';
                    }
                }
                else{
                    if($num===''){
                        $msgedit='編集したい投稿の番号を入力してください<br>';
                    }
                    if($password===''){
                        $msgpass='パスワードを入力してください<br>';
                    }
                }
        }
    }

    $str="SELECT * FROM yjdata ORDER BY id ASC";
    $result=$pdo->query($str);
    foreach($result as $val){
        echo $val["id"].' '.$val["name"].' '.$val["comment"].' '.$val["date"].'<br>';
    }


}catch(PODException $e){
    header('Content-Type: text/plain; charset=UTF-8', true, 500);
    exit($e->getMassage());
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
		<meta chaerset="UTF-8">
        <title>ミッション4</title>
	</head>
	<body>
		<form action="mission_4-1.php" method="post">
        
			<input type="text" name="name" placeholder="名前" value="<?php echo $ena; ?>" size=30/><br/>
            <input type="text" name="comment" placeholder="コメント" value="<?php echo $eco; ?>" size=50/><br/>

<?php if(is_null($enu)): ?>
            <input type= "password" name="sendpass" placeholder="パスワード"/><br/>
            <input type="submit" name="sb" value="送信"/><br/><br/>
			<input type="text" name="en" placeholder="編集対象番号" value="" size=10/><br/>
			<input type= "password" name="epass" placeholder="パスワード"/><br/>

<?php else: ?>
			<input type="hidden" name="re" value="<?php echo $enu; ?>" size=5/><br/>
<?php endif; ?>
            <input type="submit" name="eb" value="編集"/><br/><br/>

            <input type="text" name="dn" placeholder="削除対象番号" value="" size=10/><br/>
			<input type="password" name="dpass" placeholder="パスワード">
            <input type="submit" name="db" value="削除"/>


        </form>
    </body>
</html>

<?php
if($msgname!=='') echo $msgname;
if($msgcome!=='') echo $msgcome;
if($msgedit!=='') echo $msgedit;
if($msgdel!=='') echo $msgdel;
if($msgpass!=='') echo $msgpass;
if($msgsend!=='') echo $msgsend;
?>
