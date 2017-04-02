<?php
/**
 * Created by PhpStorm.
 * User: To Olympus
 * Date: 2017-02-11
 * Time: 6:00 PM
 */
?>


<h2>History</h2>
<div class = "container">

    {sort_script}
    {filterModel_script}
    {filterLine_script}
    <br/>
    <div class="row">
        <form action="" method="POST">
            <div class="col-md-2">
                <h3 style="display:inline;">Sort</h3>
                <select class="form-control" name="order" id="order">
                    <option value="timestamp">Timestamp</option>
                    <option value="model">Model</option>
                </select>
            </div>

            <div class="col-md-2">
                <h3 style="display:inline;">Filter by Line</h3>
                <select class="form-control" name="filterLine" id="filterLine">
                    <option value="all">All</option>
                    <option value="Household">Household</option>
                    <option value="Butler">Butler</option>
                    <option value="Companion">Companion</option>
                    <option value="Motely">Motely</option>
                </select>
            </div>

            <div class="col-md-2">
                <h3 style="display:inline;">Filter by Model</h3>
                <select class="form-control" name="filterModel" id="filterModel">
                    <option value="all">All</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="D">D</option>
                    <option value="E">E</option>
                    <option value="F">F</option>
                    <option value="G">G</option>
                    <option value="H">H</option>
                    <option value="I">I</option>
                    <option value="J">J</option>
                    <option value="K">K</option>
                    <option value="L">L</option>
                    <option value="M">M</option>
                    <option value="N">N</option>
                    <option value="O">O</option>
                    <option value="P">P</option>
                    <option value="Q">Q</option>
                    <option value="R">R</option>
                    <option value="S">S</option>
                    <option value="T">T</option>
                    <option value="U">U</option>
                    <option value="V">V</option>
                    <option value="W">W</option>
                    <option value="X">X</option>
                    <option value="Y">Y</option>
                    <option value="Z">Z</option>
                </select>
            </div>

            <div class="col-md-3">
                <input class="btn btn-success" type="submit" value="Sort/Filter" />
            </div>
        </form>
    </div>

    <table class = "table table-striped">
        <thead bgcolor="#f2cdb3">
            <tr>
                <td>Transaction ID</td>
                <td>Type Of Transaction</td>
                <td>Parts</td>
                <td>Cost/Profit</td>
                <td>Date of Transaction</td>
            </tr>
        <thead>
        <tbody>
            {history}
        </tbody>
    </table>
    {pagination}


</div>
