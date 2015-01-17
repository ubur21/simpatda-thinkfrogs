<div class="login">
<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST" enctype="text/html">
    <table>
        <tr><td colspan="2" class="judul">Login</td>
        </tr>
        <tr>
            <td align="left"><br>Username&nbsp;</td>
            <td align="right"><br><input type="text" class="field" size="20" name="cuser" id="focushere"></td>
		</tr>
		<tr>
            <td align="left">Password&nbsp;</td>
            <td align="right"><input type="password" class="field" size="20" name="cpass"></td>
        </tr>
<?php
/* if(REMEMBER) {
		<tr><td colspan="2" align="right"><input type="checkbox" name="cremember">&nbsp;Remember me on this computer</td>
		</tr>
} */ 
?>		
        <tr><td align="right" colspan="2"><input type="submit" value="Login" name="csubmit"></td>
        </tr>
    </table>
</form>
</div>