
<h2>Assembly & Retrun & Shipping</h2>
<div class="assemblyContent">
    <div class="assemblyDiv"">
        <form method='POST' action='{partFormAction}'>
            {thetableTop}
            {thetableTorso}
            {thetableBottom}
            <div class="twoBtn">
                <input type="submit" name="assembly"  id ="assemblyBtn" value="Assembly"/>
                <input type="submit" name="return" id = "returnBtn" value="Return to Head Office"/>
            </div>
        </form>
    </div>

    <div class="assemblyDiv"">
        <form method='POST' action='{robotFormAction}'>
            {thetableRobots}
            <div class="twoBtn">
                <input type="submit" name="shipRobot"  id ="shipBtn" value="Ship to Head Office"/>
            </div>
        </form>
    </div>
</div>

