
/** classes indexes **/
db.getCollection("classes").ensureIndex({
  "_id": NumberInt(1)
},[
  
]);

/** students indexes **/
db.getCollection("students").ensureIndex({
  "_id": NumberInt(1)
},[
  
]);

/** teachers indexes **/
db.getCollection("teachers").ensureIndex({
  "_id": NumberInt(1)
},[
  
]);

/** classes records **/
db.getCollection("classes").insert({
  "_id": ObjectId("506b0e55e7a3c66b68000000"),
  "is_active": true,
  "paid": false,
  "students": [
    {
      "name": "Wayne Grover",
      "id": "506b67a5e7a3c63e9f000001",
      "class_payment": "cash",
      "goods_payment": "n\/a",
      "goods_display": "n\/a",
      "total": "0"
    },
    {
      "name": "Test User",
      "id": "506c9057e7a3c60dcf000000",
      "class_payment": "cash",
      "goods_payment": "n\/a",
      "goods_display": "n\/a",
      "total": "0"
    },
    {
      "name": "Jimmy Jams",
      "id": "506b1defe7a3c6af72000000",
      "class_payment": "class card",
      "goods_payment": "cash",
      "goods_display": "Water ($2), ",
      "total": "2"
    }
  ],
  "teacher": "skoch",
  "timestamp": "1349193301058",
  "type": "yoga"
});

/** students records **/
db.getCollection("students").insert({
  "_id": ObjectId("506b1defe7a3c6af72000000"),
  "card": 5,
  "classes_taken_with_card": 0,
  "fullname": "Jimmy Jams",
  "total_classes_taken": 0,
  "username": "jjams"
});
db.getCollection("students").insert({
  "_id": ObjectId("506b67a5e7a3c63e9f000001"),
  "card": 0,
  "classes_taken_with_card": 0,
  "fullname": "Wayne Grover",
  "total_classes_taken": 0,
  "username": "wgrover"
});
db.getCollection("students").insert({
  "_id": ObjectId("506c9057e7a3c60dcf000000"),
  "card": 0,
  "classes_taken_with_card": 0,
  "fullname": "Test User",
  "total_classes_taken": 0,
  "username": "tuser"
});

/** system.indexes records **/
db.getCollection("system.indexes").insert({
  "name": "_id_",
  "ns": "cobra_classes.students",
  "key": {
    "_id": NumberInt(1)
  }
});
db.getCollection("system.indexes").insert({
  "name": "_id_",
  "ns": "cobra_classes.teachers",
  "key": {
    "_id": NumberInt(1)
  }
});
db.getCollection("system.indexes").insert({
  "name": "_id_",
  "ns": "cobra_classes.classes",
  "key": {
    "_id": NumberInt(1)
  }
});

/** teachers records **/
db.getCollection("teachers").insert({
  "_id": ObjectId("5069cf2ee7a3c6b21f000000"),
  "is_admin": 1,
  "last_login": "",
  "login": "nkoch",
  "name": "Nikki Koch",
  "pwd": "qwe123;",
  "session_id": ""
});
db.getCollection("teachers").insert({
  "_id": ObjectId("506a2165e7a3c68646000000"),
  "is_admin": 1,
  "last_login": "1349293834118",
  "login": "skoch",
  "name": "Stephen Koch",
  "pwd": "qwe123;",
  "session_id": "cf86e0c0da87e4d1c2a79c825b8d1c02"
});
