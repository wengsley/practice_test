Laravel 综合能力实践题 | Laravel Comprehensive Skill Practice Test
 背景 | Background
你正在开发一个支付平台，平台需要支持用户查询交易状态，并进行转账操作。  
You are developing a payment platform that needs to support users in querying transaction statuses and making transfers.  

你需要基于 Laravel 设计并实现核心功能，包括数据库设计、接口开发、队列处理、日志记录及权限控制等。  
You need to design and implement core functionalities based on Laravel, including database design, API development, queue processing, logging, and access control.  


任务要求 | Task Requirements  

1. 设计数据库结构 | Database Structure Design  
请设计以下核心数据表的结构，并提供 Laravel 的迁移文件：  
Design the following core data tables and provide Laravel migration files:  

•⁠  ⁠users（用户表 | User Table）：存储用户信息 | Stores user information.  
•⁠  ⁠transactions（交易表 | Transaction Table）：记录交易状态 | Records transaction statuses.  
•⁠  ⁠transfers（转账表 | Transfer Table）：记录用户发起的转账请求 | Records user-initiated transfer requests.  

---

2. 交易状态查询 API | Transaction Status Query API
实现一个 API 接口 ⁠ GET /api/transactions/{id} ⁠，允许用户查询交易状态，并返回以下信息：  
Implement an API endpoint ⁠ GET /api/transactions/{id} ⁠, allowing users to query the transaction status and return the following information:  

•⁠  ⁠交易金额 | Transaction amount
•⁠  ⁠交易状态（pending, completed, failed）| Transaction status (pending, completed, failed)
•⁠  ⁠交易时间 | Transaction timestamp

要求 | Requirements:
•⁠  ⁠需要添加 API 认证（如 Laravel Sanctum、Passport、JWT）。  
  API authentication is required (e.g., Laravel Sanctum, Passport, JWT).  
•⁠  ⁠仅允许交易的发起用户或管理员查询。  
  Only the transaction initiator or an administrator is allowed to query.  

3. 转账 API | Transfer API
实现 ⁠ POST /api/transfers ⁠，允许用户发起转账，并存入数据库，返回转账单号。  
Implement ⁠ POST /api/transfers ⁠, allowing users to initiate a transfer, store it in the database, and return a transfer ID.  

请求参数 | Request Parameters:
•⁠  ⁠receiver_id（接收用户 ID | Receiver user ID）  
•⁠  ⁠amount（转账金额 | Transfer amount）  

需要进行 | Required Processing:
•⁠  ⁠余额校验 | Balance validation（确保用户余额足够 | Ensure the user has sufficient balance）  
•⁠  ⁠幂等性检查 | Idempotency check（防止重复提交 | Prevent duplicate submissions）  
•⁠  ⁠事务处理 | Transaction handling（确保数据一致性 | Ensure data consistency）  

---

4. 队列处理转账 | Queue Processing for Transfers*  
转账请求不会立即完成，而是进入队列 ⁠ transfers ⁠ 进行异步处理。  
Transfer requests will not be completed immediately but will be processed asynchronously in the ⁠ transfers ⁠ queue.  

实现 Laravel 队列任务 ⁠ ProcessTransfer ⁠ | Implement the Laravel Queue Job ⁠ ProcessTransfer ⁠
*任务逻辑 | Task Logic:  
-检查用户余额 | Check user balance  
•⁠  ⁠处理扣款 & 资金转移 | Process debit & fund transfer  
•⁠  ⁠更新交易状态 | Update transaction status 
•⁠  ⁠失败重试 | Retry on failure 
•⁠  ⁠记录日志 | Log processing details（存入数据库 & Laravel 日志文件 | Store in the database & Laravel log files）  


5. 记录操作日志 | Log Operations*  
所有 API 请求（transactions、transfers）应记录操作日志。  
All API requests (⁠ transactions ⁠, ⁠ transfers ⁠) should log operations.  

使用 Laravel 事件监听（Event & Listener），当交易状态变更时，自动记录日志。  
Use Laravel event listeners (Event & Listener) to automatically log when transaction statuses change.  

6. 权限控制 | Access Control
•⁠  ⁠只有已认证用户可以发起查询和转账。  
  Only authenticated users can query and initiate transfers.  
•⁠  ⁠普通用户只能查询自己的交易。  
  Regular users can only query their own transactions.  
•⁠  ⁠管理员可以查询所有用户的交易。  
  Administrators can query transactions of all users.  

进阶要求（可选） | Advanced Requirements (Optional)  
使用 GitHub Actions 自动化实现以下功能：  
Implement GitHub Actions automation for the following:  

•⁠  ⁠代码风格检查 | Code style check*（使用 PHP CS Fixer | Using PHP CS Fixer）  
•⁠  ⁠单元测试 | Unit testing*（使用 PHPUnit | Using PHPUnit）  
•⁠  ⁠服务器自动代码部署 | Automatic server deployment*  

交付要求 | Deliverables*  
请完成任务后，将代码上传到 GitHub，并提供相关文件。  
After completing the task, upload the code to GitHub and provide the following files:  

✅ 数据库迁移文件 | Database migration files*（⁠ migrations/ ⁠）  
✅ API 控制器 | API controllers*（⁠ app/Http/Controllers/ ⁠）  
✅ 队列任务 | Queue jobs*（⁠ app/Jobs/ProcessTransfer.php ⁠）  
✅ 事件监听 | Event listeners*（⁠ app/Listeners/LogTransactionStatus.php ⁠）  
✅ 权限控制 | Access control*（⁠ Policy ⁠ 或 ⁠ Middleware ⁠）  
✅ API 文档或测试用例 | API documentation or test cases*  
✅ GitHub Actions 配置文件 | GitHub Actions configuration file*（⁠ .github/workflows/ ⁠）  

请确保代码可读性良好，遵循 Laravel 的最佳实践。  
Ensure the code is well-structured and follows Laravel best practices.