using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.IO;
using System.Diagnostics;

namespace AddUserAndGrouping
{
    class Program
    {

        /*
         * 添加成功、命令执行成功
         * The command completed successfully.
         * 账号已经存在
         * The account already exists.
         * 分配的用户组错误，已经存在
         * System error 1378 has occurred.
         */
        static private bool CmdProcess(string _str_cmdCommand)
        {
            string strRst;
            bool _bReturn = false;
            Process p = new Process();

            p.StartInfo.FileName = "cmd.exe";
            p.StartInfo.UseShellExecute = false;
            p.StartInfo.RedirectStandardInput = true;
            p.StartInfo.RedirectStandardOutput = true;
            p.StartInfo.RedirectStandardError = true;
            p.StartInfo.CreateNoWindow = true;

            p.Start();

            p.StandardInput.WriteLine("chcp 437");
            p.StandardInput.WriteLine(_str_cmdCommand);
            p.StandardInput.WriteLine("exit");
            strRst = p.StandardOutput.ReadToEnd();

            //命令执行成功
            if (strRst.IndexOf("The command completed successfully") != -1)
                _bReturn = true;
            //命令执行不成功都返回false
            else
                _bReturn = false;

            p.Close();

            return _bReturn;
        }

        static private void AddUserAndGroupingFromTxt(string txtPath)
        {
            if (!File.Exists(txtPath))
            {
                Console.WriteLine(txtPath + "文件不存在！");
                return;
            }
            
            //首先读取txt文件的每一行，然后去掉后面的空格换行
            string[] temp;
            temp = File.ReadAllLines(txtPath, Encoding.GetEncoding("gb2312"));

            for (int i = 0; i < temp.Length; i++)
            {
                temp[i] = temp[i].Trim();

                //开始执行cmd命令,添加用户，然后分配到用户组
                string str_addUser = "net user {0} 123456 /add";
                string str_grouping = "net localgroup projectuser {0} /add";

                //添加用户
                str_addUser = string.Format(str_addUser, temp[i]);

                if (CmdProcess(str_addUser))
                {
                    Console.WriteLine("添加用户：" + temp[i] + "成功");

                    //分配用户组
                    str_grouping = string.Format(str_grouping, temp[i]);

                    if (CmdProcess(str_grouping))
                    {
                        Console.WriteLine("分配用户：" + temp[i]+"  到用户组 ProjectUser " + "成功");
                    }
                    else
                    {
                        Console.WriteLine("分配用户：" + temp[i] + "  到用户组 ProjectUser " + "失败！");
                    }
                }
                else
                {
                    Console.WriteLine("添加用户：" + temp[i] + "失败！");
                }
            }
        }

        static private bool ChangeUserPwd(string _str_userName, string _str_pwd)
        {
            string _str_changePwd = "net user {0} {1}";

            _str_changePwd = string.Format(_str_changePwd, _str_userName, _str_pwd);

            if (CmdProcess(_str_changePwd))
                return true;
            else
                return false;
        }

        static int Main(string[] args)
        {
            string[] Args = Environment.GetCommandLineArgs();
            
            /*
             *                当前exe +  命令 +
             * AddUserFormTxt                    文件路径
             * ChangeUserPwd                     用户名  +  密码
             */
            if (Args.Count() == 3 && Args[1].ToString() == "AddUserFormTxt")
            {
                string str_filepath = "";
                str_filepath = Args[2].ToString();

                AddUserAndGroupingFromTxt(str_filepath);

                Console.ReadKey();
            }
            else if (Args.Count() == 4 && Args[1].ToString() == "ChangeUserPwd")
            {
                string str_username = Args[2].ToString();
                string str_pwd = Args[3].ToString();
                if (ChangeUserPwd(str_username, str_pwd))
                {
                    return 111;
                }
                else
                    return -111;
            }

            return -1;
        }

    }
}
