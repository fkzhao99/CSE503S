import json
from bson import ObjectId
from flask import Blueprint, jsonify, render_template, abort, request
from jinja2 import TemplateNotFound
from werkzeug.security import generate_password_hash, check_password_hash
from flask_jwt_extended import create_access_token, create_refresh_token, set_access_cookies, set_refresh_cookies, \
    jwt_required, get_jwt_identity, unset_jwt_cookies

cates = Blueprint('cates', __name__)

@cates.route('/addCate', methods=['POST'])
@jwt_required()
def addCate():
    objName = get_jwt_identity()
    print(objName)
    post_data = request.get_json()
    from app import catedb
    adminlist=["Work", "Study", "Play", "Sport"]
    if (catedb.find_one({'username': objName, 'cate': post_data["catename"]}) != None) or (post_data["catename"] in adminlist):
        return jsonify({
        "username": objName,
        "res" : 0,
        "msg" : "Category already exists!"
    })

    else:
        catedb.insert_one({'username': objName, 'cate': post_data["catename"]})
        return jsonify({
            "username": objName,
            "res" : 1,
            "msg" : "Add cate"
        })

@cates.route('/editCate', methods=['POST'])
@jwt_required()
def editCate():
    objName = get_jwt_identity()
    from app import catedb, eventdb
    post_data = request.get_json()
    adminlist=["Work", "Study", "Play", "Sport"]
    if (catedb.find_one({'username': objName, 'cate': post_data["newcate"]}) != None) or (post_data["newcate"] in adminlist):
        return jsonify({
        "username": objName,
        "res" : 0,
        "msg" : "Category already exists!"
    })
    catedb.update_one({'username': objName, "cate": post_data["precate"]}, {'$set': {'cate': post_data["newcate"]}})
    eventdb.update_many({'username': objName, "cate": post_data["precate"]}, {'$set': {'cate': post_data["newcate"]}})
    flag = 1
    return jsonify({
        "username": objName,
        "res" : flag,
        "msg" : "Edit cate"
    })

@cates.route('/delCate', methods=['POST'])
@jwt_required()
def delCate():
    objName = get_jwt_identity()
    from app import catedb, eventdb
    post_data = request.get_json()
    if catedb.find_one({'username': objName, 'cate': post_data["catename"]}) == None:
        return jsonify({
        "username": objName,
        "res" : 0,
        "msg" : "Category doesn't exists!"
    })
    catedb.delete_one({'username': objName, "cate": post_data["catename"]})
    eventdb.delete_many({'username': objName, "cate": post_data["catename"]})
    flag = 1
    return jsonify({
        "username": objName,
        "res" : flag,
        "msg" : "Delete cate"
    })


@cates.route('/getCates', methods=['GET'])
@jwt_required()
def listCate():
    objName = get_jwt_identity()
    from app import catedb
    # post_data = request.get_json()
    catelist = []
    for result in catedb.find({'username': objName}):
        catelist.append(result["cate"])
    # jsonStr = json.dumps(catelist)
    return jsonify({
        "username": objName,
        "catelist": catelist,
    })

    
@cates.route('/getAllCates', methods=['GET'])
@jwt_required()
def listAllCate():
    objName = get_jwt_identity()
    from app import catedb
    # post_data = request.get_json()
    catelist = []
    for result in catedb.find({'username': "Admin"}):
        catelist.append(result["cate"])
    for result in catedb.find({'username': objName}):
        catelist.append(result["cate"])
    # jsonStr = json.dumps(catelist)
    return jsonify({
        "username": objName,
        "catelist": catelist,
    })