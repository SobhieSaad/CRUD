const users=document.getElementById('user');
if(users)
{
    users.addEventListener('click',(e)=>
    {
     if(e.target.className=== 'btn btn-danger delete-user' )
     {
        if(confirm('Are you sure?'))
        {
            const id=e.target.getAttribute('data-id');
            fetch(`/users/delete/${id}`,{
                method: 'DELETE'
            }).then(res =>window.location.reload());
        }
     }  
    });
}